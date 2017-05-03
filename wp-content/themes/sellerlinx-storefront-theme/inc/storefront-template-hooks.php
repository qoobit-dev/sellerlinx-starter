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
add_action( 'sellerlinx_meta', 'sellerlinx_seo', 10 );
add_action( 'sellerlinx_home_banners', 'sellerlinx_home_videos', 10 );
add_action( 'sellerlinx_home_banners', 'sellerlinx_home_banners', 10 );
//add_action( 'storefront_before_content', 'storefront_header_widget_region', 10 );
//add_action( 'storefront_sidebar',        'storefront_get_sidebar',          10 );
//add_action( 'woocommerce_before_single_product_summary', 'sellerlinx_show_product_images', 20 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_summary_sale_price', 5 );
add_action( 'woocommerce_after_single_product', 'sellerlinx_sticky_product_summary', 999 );

/**
 * Header
 *
 */
add_action( 'before_body', 'sellerlinx_background', 99 );
add_action( 'storefront_facebook_header', 'storefront_facebook_product_search', 10 );
add_action( 'storefront_facebook_header', 'storefront_facebook_header_cart',    30 );

//add_action( 'storefront_facebook_header', 'storefront_facebook_site_branding',    20 );
add_action( 'storefront_facebook_header', 'storefront_facebook_secondary_navigation',      40 );
//add_action( 'storefront_facebook_header', 'storefront_primary_navigation_wrapper',       42 );
add_action( 'storefront_facebook_header', 'storefront_product_category_navigation',        50 );
add_action( 'storefront_header', 'sellerlinx_site_branding',        20 );
add_action( 'storefront_header', 'storefront_product_category_navigation',        50 );

add_action( 'wp_head', 'sellerlinx_site_icon', 99 );
add_action( 'wp_head',                  'sellerlinx_banner_css',         100 );
add_action( 'wp_head',                  'sellerlinx_product_css',         100 );
add_action( 'wp_head',                  'sellerlinx_google_analytics',         999 );
//add_action( 'storefront_header', 'storefront_primary_navigation',               50 );
//add_action( 'storefront_facebook_header', 'storefront_primary_navigation_wrapper_close', 68 );

/**
 * Footer
 *
 */
//add_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
//add_action( 'storefront_footer', 'storefront_credit',         20 );
add_action( 'storefront_footer',                  'storefront_handheld_footer_social_links',     10 );
add_action( 'storefront_footer',                  'storefront_handheld_footer_sitemap',     20 );
add_action( 'storefront_footer',                  'sellerlinx_languages',         999 );
add_action( 'storefront_footer',                  'storefront_handheld_footer_bar',         999 );


