<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://jruns.github.io/
 * @since      0.1.0
 *
 * @package    DisableAI
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
