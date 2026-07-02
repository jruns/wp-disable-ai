<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.1
 *
 * @package    Disable_AI
 * @subpackage Disable_AI/admin/partials
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


$disai_wpconfig_mode = false;
if( defined( 'DISAI_ENABLE_WPCONFIG_MODE' ) ) {
    if ( rest_sanitize_boolean( constant( 'DISAI_ENABLE_WPCONFIG_MODE' ) ) ) {
        $disai_wpconfig_mode = true;
    }
}
$disai_settings = (array) get_option( 'disai_settings', array() );
?>

<div class="wrap">
<h1><?php esc_html_e( 'Disable AI', 'disable-ai' ); ?></h1>
<p class="plugin_intro">Tired of plugins and themes adding AI features you don't want?<br/>Tired of getting nagged all the time to pay for AI features?<br/>This plugin helps you turn off unwanted AI features and notifications in plugins, themes, and WordPress Core.</p>

<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
<?php settings_fields( 'disable-ai' ); ?>

<ul>
<li class="itemDetail">
<h2 class="itemTitle"><?php esc_html_e( 'Disable in Plugins', 'disable-ai' ); ?></h2>

<table class="form-table">
<?php
$args = array(
    'type'              => 'plugin',
    'name'              => 'aioseo',
    'heading'           => 'All in One SEO',
    'description'       => 'Disable All in One SEO\'s AI features. Removes the Writing Assistant and AI-related buttons, menu items and tabs from the WordPress Editor.'
);
disai_output_admin_option( $args, $disai_settings, $disai_wpconfig_mode );

$args = array(
    'type'              => 'plugin',
    'name'              => 'elementor',
    'heading'           => 'Elementor',
    'description'       => 'Disable Elementor\'s AI features.'
);
disai_output_admin_option( $args, $disai_settings, $disai_wpconfig_mode );

$args = array(
    'type'              => 'plugin',
    'name'              => 'rankmath',
    'heading'           => 'Rank Math SEO',
    'description'       => 'Disable Rank Math SEO\'s AI features.'
);
disai_output_admin_option( $args, $disai_settings, $disai_wpconfig_mode );

$args = array(
    'type'              => 'plugin',
    'name'              => 'wpforms',
    'heading'           => 'WPForms',
    'description'       => 'Disable WPForms\' AI features.'
);
disai_output_admin_option( $args, $disai_settings, $disai_wpconfig_mode );

$args = array(
    'type'              => 'plugin',
    'name'              => 'yoast',
    'heading'           => 'Yoast SEO',
    'description'       => 'Disable Yoast SEO\'s AI features.'
);
disai_output_admin_option( $args, $disai_settings, $disai_wpconfig_mode );
?>
</table>

<h2 class="itemTitle"><?php esc_html_e( 'Disable in WordPress Core', 'disable-ai' ); ?></h2>

<table class="form-table">
<?php
$args = array(
    'type'              => 'core',
    'name'              => 'disable_ai',
    'heading'           => 'Disable AI',
    'description'       => 'Disable WordPress\'s built-in AI features and the Abilities API. Sets `wp_supports_ai` to false in WP 7.0.0+, and unregisters all Abilities registered by plugins in earlier WP versions.'
);
disai_output_admin_option( $args, $disai_settings, $disai_wpconfig_mode );
?>
</table>

</li>
</ul>

<p class="submit">
<input type="submit" class="button-secondary" value="<?php esc_html_e( 'Save Changes', 'disable-ai' ); ?>" />
</p>

</form>
</div>

<?php

function disai_output_admin_option( $args , $settings, $wpconfig_mode = false ) {
    $type = $args['type'] ?? '';
    $name = $args['name'] ?? '';
    $heading = $args['heading'] ?? '';
    $description = $args['description'] ?? '';

    $utility_constant = strtoupper( 'disai_' . $type . '_' . $name );
    $utility_value = null;
    $placeholder = '';
    $after_label_msg = '';

    if( defined( $utility_constant ) ) {
        $utility_value = rest_sanitize_boolean( constant( $utility_constant ) );
        $after_label_msg = "<span class='tooltip'><span class='dashicons dashicons-warning'></span><span class='tooltip-text'>This setting is currently configured in your wp-config.php file and can only be enabled or disabled there.<br/><br/>" . ( $wpconfig_mode ? "WP-Config Mode is enabled as well. Remove DISAI_ENABLE_WPCONFIG_MODE and $utility_constant from wp-config.php in order to enable/disable this setting here." : "Remove $utility_constant from wp-config.php in order to enable/disable this setting here." ) . "</span></span>";
    } else if ( ! $wpconfig_mode && array_key_exists( $type, $settings ) && array_key_exists( $name, $settings[$type] ) ) {
        $utility_value = absint( $settings[$type][$name] );
    }

    $disabled_title = "Remove $utility_constant from wp-config.php in order to configure this setting here.";
    if ( $wpconfig_mode ) {
        $disabled_title = "This setting is managed by the $utility_constant constant in wp-config.php because WP-Config Mode is enabled. Remove DISAI_ENABLE_WPCONFIG_MODE " . ( defined( $utility_constant ) ? "and $utility_constant " : "" ) . "from wp-config.php in order to configure this setting here.";
    }

    $input_output = "<input type='checkbox' name='disai_settings[$type][$name]' value='1' " . ( $utility_value ? "checked='checked'" : '' ) . ( $wpconfig_mode || defined( $utility_constant ) ? " disabled='' title='$disabled_title'" : "" ) . "/>" . $description . "$after_label_msg";

    $allowed_html = array(
        'tr' => array(
			'valign' => true
        ),
        'th' => array(
			'scope' => true
        ),
        'td' => array(),
        'label' => array(),
		'input' => array(
			'type' => true,
			'id' => true,
			'name' => true,
			'value' => true,
			'title' => true,
			'checked' => true,
			'disabled' => true
		),
		'span' => array(
			'class' => true
		),
        'p' => array(),
        'br' => array(),
    );

    $output = "<tr valign='top'>
        <th scope='row'>" . $heading . "</th>" .
        "<td><label>$input_output</label></td></tr>";
    
    echo wp_kses( $output, $allowed_html );
}