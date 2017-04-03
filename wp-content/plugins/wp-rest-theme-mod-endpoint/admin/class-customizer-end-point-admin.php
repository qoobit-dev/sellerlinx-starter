<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       vishabasnet23.com
 * @since      1.0.0
 *
 * @package    Customizer_End_Point
 * @subpackage Customizer_End_Point/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Customizer_End_Point
 * @subpackage Customizer_End_Point/admin
 * @author     Bishal Basnet <vishalbasnet23@gmail.com>
 */
class Customizer_End_Point_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Customizer_End_Point_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Customizer_End_Point_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/customizer-end-point-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Customizer_End_Point_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Customizer_End_Point_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/customizer-end-point-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function customizer_endpoint_init() {
		register_rest_route( 'themes/v1', '/customizer-fields/', array(
			'methods' => 'GET',
			'callback' => array( $this, 'get_active_theme_customizer_fields' ),
		) );
	}

	public function get_active_theme_customizer_fields() {
		$current_active_theme = wp_get_theme();
		$current_active_theme_name = $current_active_theme->get('Name');
		$theme_mod = get_option( 'theme_mods_'.$current_active_theme_name );
		return $theme_mod;
	}

}
