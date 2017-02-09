<?php
/**
 * Facebook Send Button Initializer
 *
 * @since 	1.0.0
 * @package WPFBS
 */

// die if accessed directly.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * WPFBS Settings
 *
 * @since 1.0.0
 */

global $settings_arr;
$settings_arr = get_option( 'wpfbs_settings' );

if ( file_exists( WPFBS_DIR . '/admin/settings.php' ) ) {
    require_once( WPFBS_DIR . '/admin/settings.php' );
}
if ( file_exists( WPFBS_DIR . '/includes/wpfbs-functions.php' ) ) {
    require_once( WPFBS_DIR . '/includes/wpfbs-functions.php' );
}
