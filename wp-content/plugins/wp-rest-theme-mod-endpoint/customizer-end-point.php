<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              vishabasnet23.com
 * @since             1.0.0
 * @package           Customizer_End_Point
 *
 * @wordpress-plugin
 * Plugin Name:       Customizer Field EndPoint
 * Plugin URI:        codepixelzmedia.com.np
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Bishal Basnet
 * Author URI:        vishabasnet23.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       customizer-end-point
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-customizer-end-point-activator.php
 */
function activate_customizer_end_point() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customizer-end-point-activator.php';
	Customizer_End_Point_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-customizer-end-point-deactivator.php
 */
function deactivate_customizer_end_point() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-customizer-end-point-deactivator.php';
	Customizer_End_Point_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_customizer_end_point' );
register_deactivation_hook( __FILE__, 'deactivate_customizer_end_point' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-customizer-end-point.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_customizer_end_point() {

	$plugin = new Customizer_End_Point();
	$plugin->run();

}
run_customizer_end_point();
