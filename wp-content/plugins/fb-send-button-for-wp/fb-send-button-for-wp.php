<?php 
/**
 * Plugin Name: FB Send Button for WP
 * Plugin URI: http://code-freaks.net/
 * Description: Adds Facebook send button to your WordPress posts or pages.
 * Author: Maavuz Saif, Code Freaks
 * Author URI: http://maav.uz/
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package WPFBS
 */

// die if accessed directly.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Define global constants.
 *
 * @since 1.0.0
 */
// Plugin version.
if ( ! defined( 'WPFBS_VERSION' ) ) {
    define( 'WPFBS_VERSION', '1.0.0' );
}

if ( ! defined( 'WPFBS_NAME' ) ) {
    define( 'WPFBS_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined('WPFBS_URL' ) ) {
    define( 'WPFBS_URL', WP_PLUGIN_URL . '/' . WPFBS_NAME );
}

if ( ! defined('WPFBS_DIR' ) ) {
    define( 'WPFBS_DIR', WP_PLUGIN_DIR . '/' . WPFBS_NAME );
}


/**
 * Plugin Initializer
 *
 * @since 1.0.0
 */
if ( file_exists( WPFBS_DIR . '/includes/wpfbs-init.php' ) ) {
    require_once( WPFBS_DIR . '/includes/wpfbs-init.php' );
}
