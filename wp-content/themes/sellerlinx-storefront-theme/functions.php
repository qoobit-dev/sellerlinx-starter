<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */


/**
 * Remove default storefront sidebar from left or right
 */
add_action( 'init', 'sf_child_remove_parent_theme_stuff', 0 );
function sf_child_remove_parent_theme_stuff() {
	remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
}

/**
 * Add our own customizer on top of storefront's
 */

include_once dirname(__FILE__)  . '/inc/customizer.php';

/**
 * Sellerlinx Hooks
 */
include_once 'inc/storefront-template-hooks.php';
include_once 'inc/storefront-template-functions.php';
include_once 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
include_once 'inc/woocommerce/storefront-woocommerce-template-functions.php';


/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	//return "https://shop.sellerlinx.com/sandbox/";
	return get_home_url();
}

add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );


// define the woocommerce_breadcrumb_home_url callback (override is needed for wordpress)
function filter_woocommerce_breadcrumb_home_url( $home_url ) { 
	//return "https://shop.sellerlinx.com/sandbox/";
    return get_home_url(); 
}; 
         
// add the filter 
add_filter( 'woocommerce_breadcrumb_home_url', 'filter_woocommerce_breadcrumb_home_url', 10, 1 ); 


remove_action( 'template_redirect', 'wc_send_frame_options_header' );
//remove_action( 'storefront_header', 'storefront_primary_navigation' );


