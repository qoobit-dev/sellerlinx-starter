<?php
/**
 * Storefront hooks
 *
 * @package storefront
 */

/**
 * General
 *
 */
//add_action( 'storefront_before_content', 'storefront_header_widget_region', 10 );
//add_action( 'storefront_sidebar',        'storefront_get_sidebar',          10 );

/**
 * Header
 *
 */
//add_action( 'storefront_facebook_header', 'storefront_facebook_site_branding',    20 );
add_action( 'storefront_facebook_header', 'storefront_facebook_secondary_navigation',      40 );
//add_action( 'storefront_facebook_header', 'storefront_primary_navigation_wrapper',       42 );
add_action( 'storefront_facebook_header', 'storefront_product_category_navigation',        50 );
add_action( 'storefront_header', 'storefront_product_category_navigation',        50 );
//add_action( 'storefront_header', 'storefront_primary_navigation',               50 );
//add_action( 'storefront_facebook_header', 'storefront_primary_navigation_wrapper_close', 68 );

/**
 * Footer
 *
 */
//add_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
//add_action( 'storefront_footer', 'storefront_credit',         20 );

