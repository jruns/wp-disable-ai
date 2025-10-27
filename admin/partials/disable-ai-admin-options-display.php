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

$settings = (array) get_option( 'disaai_settings', array() );
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
output_admin_option( $args, $settings );

$args = array(
    'type'              => 'plugin',
    'name'              => 'elementor',
    'heading'           => 'Elementor',
    'description'       => 'Disable Elementor\'s AI features.'
);
output_admin_option( $args, $settings );

$args = array(
    'type'              => 'plugin',
    'name'              => 'wpforms',
    'heading'           => 'WPForms',
    'description'       => 'Disable WPForms\' AI features.'
);
output_admin_option( $args, $settings );

$args = array(
    'type'              => 'plugin',
    'name'              => 'yoast',
    'heading'           => 'Yoast',
    'description'       => 'Disable Yoast\'s AI features.'
);
output_admin_option( $args, $settings );
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

function output_admin_option( $args , $settings ) {
    $type = $args['type'] ?? '';
    $name = $args['name'] ?? '';
    $heading = $args['heading'] ?? '';
    $description = $args['description'] ?? '';

    $utility_constant = strtoupper( 'disaai_' . $type . '_' . $name );
    $utility_value = null;
    $placeholder = '';
    $after_label_msg = '';
    if( defined( $utility_constant ) ) {
        $utility_value = constant( $utility_constant );
        $after_label_msg = "<span class='tooltip'><span class='dashicons dashicons-warning'></span><span class='tooltip-text'>This setting is currently configured in your wp-config.php file and can only be enabled or disabled there.<br/><br/>Remove $utility_constant from wp-config.php in order to enable/disable this setting here.</span></span>";
    } else if ( array_key_exists( $type, $settings ) && array_key_exists( $name, $settings[$type] ) ) {
        $utility_value = absint( $settings[$type][$name] );
    }

    $input_output = "<input type='checkbox' name='disaai_settings[$type][$name]' value='1' " . ( $utility_value ? "checked='checked'" : '' ) . ( defined( $utility_constant ) ? ' disabled' : '' ) . "/>" . $description . "$after_label_msg";

    $allowed_html = array(
        'tr' => array(
			'valign' => array(),
        ),
        'th' => array(
			'scope' => array(),
        ),
        'td' => array(),
        'label' => array(),
		'input' => array(
			'type' => array(),
			'id' => array(),
			'name' => array(),
			'value' => array(),
			'checked' => array(),
			'disabled' => array(),
		),
		'span' => array(
			'class' => array(),
		),
        'p' => array(),
        'br' => array(),
    );

    $output = "<tr valign='top'>
        <th scope='row'>" . $heading . "</th>" .
        "<td><label>$input_output</label></td></tr>";
    
    echo wp_kses( $output, $allowed_html );
}