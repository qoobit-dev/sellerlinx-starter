<?php
/**
 * Storefront Customizer Class
 *
 * @author   WooThemes
 * @package  storefront
 * @since    2.0.0
 */



add_action( "customize_register", "sellerlinx_customize_register" );
function sellerlinx_customize_register( $wp_customize ) {
	/**
	 * Columns
	 */
	$wp_customize->add_section( 'storefront_layout_columns' , array(
		'title'      			=> __( 'Product Grid Settings', 'storefront' ),
		'priority'   			=> 50,
	) );

	/**
	 * Standard Column Settings
	 */
	$wp_customize->add_setting( 'storefront_layout_columns', array(
		'default'           	=> apply_filters( 'storefront_default_column_count', '3' ),
	) );

	$wp_customize->add_control( 'storefront_layout_columns', array(
		'label'	   				=> __( 'Standard Products Per Row', 'storefront' ),
		'section'  				=> 'storefront_layout_columns',
		'settings' 				=> 'storefront_layout_columns',
		'priority' 				=> 10,
	) );

	/**
	 * Standard Products Pagination
	 */
	$wp_customize->add_setting( 'storefront_layout_products_per_page', array(
		'default'           	=> apply_filters( 'storefront_layout_default_products_per_page', '12' ),
	) );

	$wp_customize->add_control( 'storefront_layout_products_per_page', array(
		'label'	   				=> __( 'Standard Products Per Page', 'storefront' ),
		'section'  				=> 'storefront_layout_columns',
		'settings' 				=> 'storefront_layout_products_per_page',
		'priority' 				=> 10,
	) );

	/**
	 * Facebook Column Settings
	 */
	$wp_customize->add_setting( 'storefront_layout_facebook_columns', array(
		'default'           	=> apply_filters( 'storefront_default_facebook_column_count', '3' ),
	) );

	$wp_customize->add_control( 'storefront_layout_facebook_columns', array(
		'label'	   				=> __( 'Facebook Products Per Row', 'storefront' ),
		'section'  				=> 'storefront_layout_columns',
		'settings' 				=> 'storefront_layout_facebook_columns',
		'priority' 				=> 10,
	) );


	/**
	 * Facebook Products Pagination
	 */
	$wp_customize->add_setting( 'storefront_layout_facebook_products_per_page', array(
		'default'           	=> apply_filters( 'storefront_layout_facebook_default_products_per_page', '12' ),
	) );

	$wp_customize->add_control( 'storefront_layout_facebook_products_per_page', array(
		'label'	   				=> __( 'Facebook Products Per Page', 'storefront' ),
		'section'  				=> 'storefront_layout_columns',
		'settings' 				=> 'storefront_layout_facebook_products_per_page',
		'priority' 				=> 10,
	) );


}


		