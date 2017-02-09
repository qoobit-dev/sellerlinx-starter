<?php 
/**
 * Facebook Send Button Settings
 *
 * @since 	1.0.0
 * @package WPFBS
 */

// die if accessed directly.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !is_admin() ) return; // Not where we want to be, Bail!

add_action( 'admin_menu', 'wpfbs_add_admin_menu' );
add_action( 'admin_init', 'wpfbs_settings_init' );


function wpfbs_add_admin_menu(  ) { 

	add_options_page( 'Facebook Send Button', 'Facebook Send Button', 'manage_options', 'fb-send-button-for-wp', 'wpfbs_options_page' );

}


function wpfbs_settings_init(  ) { 

	register_setting( 'pluginPage', 'wpfbs_settings' );

	add_settings_section(
		'wpfbs_pluginPage_section', 
		__( 'Facebook Send Button for WordPress', 'wpfbs' ), 
		'', 
		'pluginPage'
	);

	add_settings_field( 
		'wpfbs_enable_button', 
		__( 'Enable Send Button', 'wpfbs' ), 
		'wpfbs_enable_button_render', 
		'pluginPage', 
		'wpfbs_pluginPage_section' 
	);

	add_settings_field( 
		'wpfbs_display_on', 
		__( 'Display on', 'wpfbs' ), 
		'wpfbs_display_on_render', 
		'pluginPage', 
		'wpfbs_pluginPage_section' 
	);

	add_settings_field( 
		'wpfbs_button_location', 
		__( 'Button Location', 'wpfbs' ), 
		'wpfbs_button_location_render', 
		'pluginPage', 
		'wpfbs_pluginPage_section' 
	);


}


function wpfbs_enable_button_render(  ) { 

	$options = get_option( 'wpfbs_settings' );
	?>
	<input type='checkbox' name='wpfbs_settings[wpfbs_enable_button]' <?php checked( $options['wpfbs_enable_button'], 1 ); ?> value='1'>
	<?php

}


function wpfbs_display_on_render(  ) { 

	$options = get_option( 'wpfbs_settings' );
	?>
	<select name='wpfbs_settings[wpfbs_display_on]'>
		<option value='pages' <?php selected( $options['wpfbs_display_on'], 'pages' ); ?>>Pages</option>
		<option value='posts' <?php selected( $options['wpfbs_display_on'], 'posts' ); ?>>Posts</option>
		<option value='both' <?php selected( $options['wpfbs_display_on'], 'both' ); ?>>Both</option>
	</select>

<?php

}


function wpfbs_button_location_render(  ) { 

	$options = get_option( 'wpfbs_settings' );
	?>
	<select name='wpfbs_settings[wpfbs_button_location]'>
		<option value='before_content' <?php selected( $options['wpfbs_button_location'], 'before_content' ); ?>>Before Content</option>
		<option value='after_content' <?php selected( $options['wpfbs_button_location'], 'after_content' ); ?>>After Content</option>
		<option value='both_locations' <?php selected( $options['wpfbs_button_location'], 'both_locations' ); ?>>Both Locations</option>
	</select>

<?php

}


function wpfbs_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

		<p>Note: you can also use <code>[wpfbs link="http://example.com/"]</code> shortcode to display Facebook Send Button anywhere you want :)</p>
	</form>
	<?php
}

