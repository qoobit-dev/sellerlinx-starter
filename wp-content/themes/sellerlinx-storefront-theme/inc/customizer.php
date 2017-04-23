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
	 * Product Customization
	 */
	$wp_customize->add_section( 'sellerlinx_products' , array(
		'title'      			=> __( 'Products', 'storefront' ),
		'priority'   			=> 40,
	) );

	$wp_customize->add_setting( 'sellerlinx_product_border_size', array(
		'default'           	=> apply_filters( 'sellerlinx_default_product_border_size', '0' ),
	) );

	$wp_customize->add_control( 'sellerlinx_product_border_size', array(
		'label'	   				=> __( 'Border Size', 'storefront' ),
		'section'  				=> 'sellerlinx_products',
		'settings' 				=> 'sellerlinx_product_border_size',
		'priority' 				=> 10,
	) );


	$wp_customize->add_setting( 'sellerlinx_product_border_color', array(
		'default'           	=> apply_filters( 'sellerlinx_default_background_color', '#FFFFFF' ),
	) );
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'sellerlinx_product_border_color', 
		array(
			'label'      => __( 'Border Color', 'storefront' ),
			'section'    => 'sellerlinx_products',
			'settings'   => 'sellerlinx_product_border_color',
		) ) 
	);




	/**
	 * WooCommerce Customization
	 */
	$wp_customize->add_section( 'sellerlinx_keys' , array(
		'title'      			=> __( 'Keys', 'storefront' ),
		'priority'   			=> 40,
	) );

	/**
	 * WooComemrce Key Settings
	 */
	$wp_customize->add_setting( 'sellerlinx_woo_consumer_key', array(
		'default'           	=> apply_filters( 'sellerlinx_default_woo_consumer_key', 'ck_' ),
	) );

	$wp_customize->add_control( 'sellerlinx_woo_consumer_key', array(
		'label'	   				=> __( 'WooCommerce Consumer Key', 'storefront' ),
		'section'  				=> 'sellerlinx_keys',
		'settings' 				=> 'sellerlinx_woo_consumer_key',
		'priority' 				=> 10,
	) );

	$wp_customize->add_setting( 'sellerlinx_woo_consumer_secret', array(
		'default'           	=> apply_filters( 'sellerlinx_default_woo_consumer_secret', 'cs_' ),
	) );

	$wp_customize->add_control( 'sellerlinx_woo_consumer_secret', array(
		'label'	   				=> __( 'WooCommerce Consumer Secret', 'storefront' ),
		'section'  				=> 'sellerlinx_keys',
		'settings' 				=> 'sellerlinx_woo_consumer_secret',
		'priority' 				=> 10,
	) );

	/**
	 * Banner Customization
	 */
	$wp_customize->add_section( 'sellerlinx_banners' , array(
		'title'      			=> __( 'Banners', 'storefront' ),
		'priority'   			=> 40,
	) );

	/**
	 * Banner Settings
	 */
	$wp_customize->add_setting( 'sellerlinx_banner_height', array(
		'default'           	=> apply_filters( 'sellerlinx_default_banner_height', '500' ),
	) );

	$wp_customize->add_control( 'sellerlinx_banner_height', array(
		'label'	   				=> __( 'Desktop Height', 'storefront' ),
		'section'  				=> 'sellerlinx_banners',
		'settings' 				=> 'sellerlinx_banner_height',
		'priority' 				=> 10,
	) );

	$wp_customize->add_setting( 'sellerlinx_mobile_banner_height', array(
		'default'           	=> apply_filters( 'sellerlinx_default_banner_height', '500' ),
	) );

	$wp_customize->add_control( 'sellerlinx_mobile_banner_height', array(
		'label'	   				=> __( 'Mobile Height', 'storefront' ),
		'section'  				=> 'sellerlinx_banners',
		'settings' 				=> 'sellerlinx_mobile_banner_height',
		'priority' 				=> 10,
	) );
	

	$wp_customize->add_setting( 'sellerlinx_banner_size', array(
		'default'           	=> apply_filters( 'sellerlinx_default_banner_size', 'cover' ),
	) );

	$wp_customize->add_control( 'sellerlinx_banner_size', array(
		'label'	   				=> __( 'Background Position', 'storefront' ),
		'section'  				=> 'sellerlinx_banners',
		'settings' 				=> 'sellerlinx_banner_size',
		'priority' 				=> 10,
		'type'     => 'radio',
		'choices'  => array(
			'auto'  => 'Default',
			'100% auto'  => 'Full Width',
			'auto 100%' => 'Full Height',
			'cover' => 'Cover',
		)
	) );

	$wp_customize->add_setting( 'sellerlinx_banner_configuration', array(
		'default'           	=> apply_filters( 'sellerlinx_default_banner_configuration', 'full' ),
	) );

	$wp_customize->add_control( 'sellerlinx_banner_configuration', array(
		'label'	   				=> __( 'Configuration', 'storefront' ),
		'section'  				=> 'sellerlinx_banners',
		'settings' 				=> 'sellerlinx_banner_configuration',
		'priority' 				=> 10,
		'type'     => 'radio',
		'choices'  => array(
			'full'  => 'Full Width',
			'contained'  => 'Contained',
		)
	) );

	$wp_customize->add_setting( 'sellerlinx_banner_transition', array(
		'default'           	=> apply_filters( 'sellerlinx_default_banner_transition', 'fade' ),
	) );

	$wp_customize->add_control( 'sellerlinx_banner_transition', array(
		'label'	   				=> __( 'Transition', 'storefront' ),
		'section'  				=> 'sellerlinx_banners',
		'settings' 				=> 'sellerlinx_banner_transition',
		'priority' 				=> 10,
		'type'     => 'radio',
		'choices'  => array(
			'fade'  => 'Fade',
			'slide-horizontal'  => 'Slide Horizontal',
			'slide-vertical'  => 'Slide Vertical',
		)
	) );

	$wp_customize->add_section( 'sellerlinx_banner_transition_duration' , array(
		'title'      			=> __( 'Tracking', 'storefront' ),
		'priority'   			=> 40,
	) );
	$wp_customize->add_setting( 'sellerlinx_banner_transition_duration', array(
		'default'           	=> apply_filters( 'sellerlinx_default_banner_transition_duration', '5000' ),
		) );
	$wp_customize->add_control( 'sellerlinx_banner_transition_duration', array(
		'label'	   				=> __( 'Duration (ms)', 'storefront' ),
		'section'  				=> 'sellerlinx_banners',
		'settings' 				=> 'sellerlinx_banner_transition_duration',
		'priority' 				=> 10,
	) );

	/**
	 * Tracking Customization
	 */
	$wp_customize->add_section( 'sellerlinx_tracking' , array(
		'title'      			=> __( 'Tracking', 'storefront' ),
		'priority'   			=> 40,
	) );
	$wp_customize->add_setting( 'sellerlinx_google_analytics', array() );
	$wp_customize->add_control( 'sellerlinx_google_analytics', array(
		'label'	   				=> __( 'Google Analytics', 'storefront' ),
		'section'  				=> 'sellerlinx_tracking',
		'settings' 				=> 'sellerlinx_google_analytics',
		'type'					=> 'text',
		'priority' 				=> 10,
	) );

	$wp_customize->add_setting( 'sellerlinx_google_remarketing_tag', array() );
	$wp_customize->add_control( 'sellerlinx_google_remarketing_tag', array(
		'label'	   				=> __( 'Google Remarketing Tag', 'storefront' ),
		'section'  				=> 'sellerlinx_tracking',
		'settings' 				=> 'sellerlinx_google_remarketing_tag',
		'type'					=> 'text',
		'priority' 				=> 10,
	) );


	/**
	 * Additional Customization
	 */
	$wp_customize->add_section( 'storefront_customizations' , array(
		'title'      			=> __( 'Additional Customizations', 'storefront' ),
		'priority'   			=> 40,
	) );
	/**
	 * Logo Settings
	 */
	$wp_customize->add_setting( 'sellerlinx_logo_url', array(
		'default'           	=> apply_filters( 'sellerlinx_default_logo', 'https://woo.sellerlinx.com/img/logo.png' ),
	) );

	$wp_customize->add_control( 'sellerlinx_logo_url', array(
		'label'	   				=> __( 'Logo URL', 'storefront' ),
		'section'  				=> 'storefront_customizations',
		'settings' 				=> 'sellerlinx_logo_url',
		'priority' 				=> 10,
	) );
	/**
	 * Icon Settings
	 */
	$wp_customize->add_setting( 'sellerlinx_icon_url', array(
		'default'           	=> apply_filters( 'sellerlinx_default_icon', 'https://woo.sellerlinx.com/img/icon1024.png' ),
	) );

	$wp_customize->add_control( 'sellerlinx_icon_url', array(
		'label'	   				=> __( 'Icon URL', 'storefront' ),
		'section'  				=> 'storefront_customizations',
		'settings' 				=> 'sellerlinx_icon_url',
		'priority' 				=> 10,
	) );

	$wp_customize->add_setting( 'sellerlinx_content_background_color', array(
		'default'           	=> apply_filters( 'sellerlinx_content_background_color', '#FFFFFF' ),
	) );
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'sellerlinx_content_background_color', 
		array(
			'label'      => __( 'Content Background Color', 'storefront' ),
			'section'    => 'storefront_customizations',
			'settings'   => 'sellerlinx_content_background_color',
		) ) 
	);
	$wp_customize->add_setting( 'sellerlinx_background_url', array(
		'default'           	=> apply_filters( 'sellerlinx_default_background', '' ),
	) );

	$wp_customize->add_control( 'sellerlinx_background_url', array(
		'label'	   				=> __( 'Background URL', 'storefront' ),
		'section'  				=> 'storefront_customizations',
		'settings' 				=> 'sellerlinx_background_url',
		'priority' 				=> 10,
	) );

	$wp_customize->add_setting( 'sellerlinx_background_repeat', array(
		'default'           	=> apply_filters( 'sellerlinx_default_background_position', 'repeat' ),
	) );

	$wp_customize->add_control( 'sellerlinx_background_repeat', array(
		'label'	   				=> __( 'Background Position', 'storefront' ),
		'section'  				=> 'storefront_customizations',
		'settings' 				=> 'sellerlinx_background_repeat',
		'priority' 				=> 10,
		'type'     => 'radio',
		'choices'  => array(
			'no-repeat'  => 'No Repeat',
			'repeat' => 'Repeat',
		)
	) );

	$wp_customize->add_setting( 'sellerlinx_background_size', array(
		'default'           	=> apply_filters( 'sellerlinx_default_background_size', 'cover' ),
	) );

	$wp_customize->add_control( 'sellerlinx_background_size', array(
		'label'	   				=> __( 'Background Size', 'storefront' ),
		'section'  				=> 'storefront_customizations',
		'settings' 				=> 'sellerlinx_background_size',
		'priority' 				=> 10,
		'type'     => 'radio',
		'choices'  => array(
			'auto'  => 'Default',
			'100% auto'  => 'Full Width',
			'auto 100%' => 'Full Height',
			'cover' => 'Cover',
		)
	) );


	$wp_customize->add_setting( 'sellerlinx_background_attachment', array(
		'default'           	=> apply_filters( 'sellerlinx_default_background_attachment', 'fixed' ),
	) );

	$wp_customize->add_control( 'sellerlinx_background_attachment', array(
		'label'	   				=> __( 'Background Attachment', 'storefront' ),
		'section'  				=> 'storefront_customizations',
		'settings' 				=> 'sellerlinx_background_attachment',
		'priority' 				=> 10,
		'type'     => 'radio',
		'choices'  => array(
			'scroll'  => 'Scroll',
			'fixed' => 'Fixed',
		)
	) );


	$wp_customize->add_setting( 'sellerlinx_custom_main_menu', array(
		'default'           	=> apply_filters( 'sellerlinx_default_custom_main_manu', 'default' ),
	) );

	$wp_customize->add_control( 'sellerlinx_custom_main_menu', array(
		'label'	   				=> __( 'Main Menu Option', 'storefront' ),
		'section'  				=> 'storefront_customizations',
		'settings' 				=> 'sellerlinx_custom_main_menu',
		'priority' 				=> 10,
		'type'     => 'radio',
		'choices'  => array(
			'default'  => 'Alphabetical Ascending',
			'custom'  => 'Custom',
		)
	) );

	
	
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


		