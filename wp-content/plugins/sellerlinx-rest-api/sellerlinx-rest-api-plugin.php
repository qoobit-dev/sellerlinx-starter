<?php
/*
Plugin Name: Sellerlinx REST API Extension
Plugin URL: https://sellerlinx.com
Version: 1.0
Author: Warren Wang @ Sellerlinx
Author URL: https://qoobit.com
Description: This plugin creates additional custom endpoints using the WP REST API v2 specifically for modifications via the Sellerlinx platform.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function sellerlinx_auth(){
    
    $headers = getallheaders();
    
    if(empty($headers['Authorization'])&&empty($headers['authorization'])) return false;
    
    $auth;
    
    if(!empty($headers['Authorization'])) $auth = $headers['Authorization'];
    else if(!empty($headers['authorization'])) $auth = $headers['authorization'];
    

    $parts = explode(" ",$auth);
    
    if($parts[0]!="Basic") return false;
    
    $parts = explode(":",base64_decode($parts[1]));

    $username=$parts[0];
    $password=$parts[1];

    $check = wp_authenticate_username_password(NULL,$username,$password);
    if(is_wp_error($check)) return false;
    
    return true;
}


/*
 * Theme REST 
 */
function sellerlinx_theme_get_settings(  $data ) {
    
    $data = array();
    $data['icon_url'] = get_theme_mod( 'sellerlinx_icon_url' ); 
    $data['logo_url'] = get_theme_mod( 'sellerlinx_logo_url' ); 
    $data['product_border_size'] = get_theme_mod( 'sellerlinx_product_border_size' ); 
    $data['product_border_color'] = substr(get_theme_mod( 'sellerlinx_product_border_color' ),1); 
    $data['facebook_products_per_page'] = get_theme_mod( 'storefront_layout_facebook_products_per_page' ); 
    $data['products_per_page'] = get_theme_mod( 'storefront_layout_products_per_page' ); 
    $data['facebook_columns'] = get_theme_mod( 'storefront_layout_facebook_columns' ); 
    $data['columns'] = get_theme_mod( 'storefront_layout_columns' ); 

    $data['background_color'] = get_theme_mod( 'background_color' ); 
    $data['background_url'] = get_theme_mod( 'sellerlinx_background_url' ); 
    $data['content_background_color'] = get_theme_mod( 'sellerlinx_content_background_color' ); 
    $data['background_repeat'] = get_theme_mod( 'sellerlinx_background_repeat' ); 
    $data['background_size'] = get_theme_mod( 'sellerlinx_background_size' ); 
    $data['background_attachment'] = get_theme_mod( 'sellerlinx_background_attachment' ); 

    $data['header_background_color'] = substr(get_theme_mod( 'storefront_header_background_color' ),1); 
    $data['header_text_color'] = substr(get_theme_mod( 'storefront_header_text_color' ),1); 
    $data['header_link_color'] = substr(get_theme_mod( 'storefront_header_link_color' ),1); 

    $data['footer_background_color'] = substr(get_theme_mod( 'storefront_footer_background_color' ),1); 
    $data['footer_heading_color'] = substr(get_theme_mod( 'storefront_footer_heading_color' ),1); 
    $data['footer_text_color'] = substr(get_theme_mod( 'storefront_footer_text_color' ),1); 
    $data['footer_link_color'] = substr(get_theme_mod( 'storefront_footer_link_color' ),1); 

    $data['button_background_color'] = substr(get_theme_mod( 'storefront_button_background_color' ),1); 
    $data['button_text_color'] = substr(get_theme_mod( 'storefront_button_text_color' ),1); 
    $data['button_alt_background_color'] = substr(get_theme_mod( 'storefront_button_alt_background_color' ),1); 
    $data['button_alt_text_color'] = substr(get_theme_mod( 'storefront_button_alt_text_color' ),1); 

    //custom css is stored in a post
    $post = get_post(84);
    $data['custom_css']=$post->post_content;
    
    $response = new WP_REST_Response($data);
    return $response;

}

function sellerlinx_theme_update_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();
    $source_data['icon_url'] = get_theme_mod( 'sellerlinx_icon_url' );
    if(!is_null($json_input->icon_url)) $source_data['icon_url']=$json_input->icon_url;
    $source_data['logo_url'] = get_theme_mod( 'sellerlinx_logo_url' );
    if(!is_null($json_input->logo_url)) $source_data['logo_url']=$json_input->logo_url; 
    $source_data['product_border_size'] = get_theme_mod( 'sellerlinx_product_border_size' ); 
    if(!is_null($json_input->product_border_size)) $source_data['product_border_size']=$json_input->product_border_size; 
    $source_data['product_border_color'] = get_theme_mod( 'sellerlinx_product_border_color' ); 
    if(!empty($json_input->product_border_color)) $source_data['product_border_color']=$json_input->product_border_color; 
    $source_data['facebook_products_per_page'] = get_theme_mod( 'storefront_layout_facebook_products_per_page' ); 
    if(!is_null($json_input->facebook_products_per_page)) $source_data['facebook_products_per_page']=$json_input->facebook_products_per_page; 
    $source_data['products_per_page'] = get_theme_mod( 'storefront_layout_products_per_page' ); 
    if(!is_null($json_input->products_per_page)) $source_data['products_per_page']=$json_input->products_per_page; 
    $source_data['facebook_columns'] = get_theme_mod( 'storefront_layout_facebook_columns' ); 
    if(!is_null($json_input->facebook_columns)) $source_data['facebook_columns']=$json_input->facebook_columns; 
    $source_data['columns'] = get_theme_mod( 'storefront_layout_columns' ); 
    if(!is_null($json_input->columns)) $source_data['columns']=$json_input->columns; 

    $source_data['background_color'] = get_theme_mod( 'background_color' ); 
    if(!empty($json_input->background_color)) $source_data['background_color']=$json_input->background_color; 
    $source_data['background_url'] = get_theme_mod( 'sellerlinx_background_url' ); 
    if(!is_null($json_input->background_url)) $source_data['background_url']=$json_input->background_url; 
    $source_data['content_background_color'] = get_theme_mod( 'sellerlinx_content_background_color' ); 
    if(!empty($json_input->content_background_color)) $source_data['content_background_color']=$json_input->content_background_color; 
    $source_data['background_repeat'] = get_theme_mod( 'sellerlinx_background_repeat' ); 
    if(!empty($json_input->background_repeat)) $source_data['background_repeat']=$json_input->background_repeat; 
    $source_data['background_size'] = get_theme_mod( 'sellerlinx_background_size' ); 
    if(!empty($json_input->background_size)) $source_data['background_size']=$json_input->background_size; 
    $source_data['background_attachment'] = get_theme_mod( 'sellerlinx_background_attachment' ); 
    if(!empty($json_input->background_attachment)) $source_data['background_attachment']=$json_input->background_attachment; 

    $source_data['header_background_color'] = get_theme_mod( 'storefront_header_background_color' ); 
    if(!empty($json_input->header_background_color)) $source_data['header_background_color']=$json_input->header_background_color; 
    $source_data['header_text_color'] = get_theme_mod( 'storefront_header_text_color' ); 
    if(!empty($json_input->header_text_color)) $source_data['header_text_color']=$json_input->header_text_color; 
    $source_data['header_link_color'] = get_theme_mod( 'storefront_header_link_color' ); 
    if(!empty($json_input->header_link_color)) $source_data['header_link_color']=$json_input->header_link_color; 

    $source_data['footer_background_color'] = get_theme_mod( 'storefront_footer_background_color' ); 
    if(!empty($json_input->footer_background_color)) $source_data['footer_background_color']=$json_input->footer_background_color; 
    $source_data['footer_heading_color'] = get_theme_mod( 'storefront_footer_heading_color' ); 
    if(!empty($json_input->footer_heading_color)) $source_data['footer_heading_color']=$json_input->footer_heading_color; 
    $source_data['footer_text_color'] = get_theme_mod( 'storefront_footer_text_color' ); 
    if(!empty($json_input->footer_text_color)) $source_data['footer_text_color']=$json_input->footer_text_color; 
    $source_data['footer_link_color'] = get_theme_mod( 'storefront_footer_link_color' ); 
    if(!empty($json_input->footer_link_color)) $source_data['footer_link_color']=$json_input->footer_link_color; 

    $source_data['button_background_color'] = get_theme_mod( 'storefront_button_background_color' ); 
    if(!empty($json_input->button_background_color)) $source_data['button_background_color']=$json_input->button_background_color; 
    $source_data['button_text_color'] = get_theme_mod( 'storefront_button_text_color' ); 
    if(!empty($json_input->button_text_color)) $source_data['button_text_color']=$json_input->button_text_color; 
    $source_data['button_alt_background_color'] = get_theme_mod( 'storefront_button_alt_background_color' ); 
    if(!empty($json_input->button_alt_background_color)) $source_data['button_alt_background_color']=$json_input->button_alt_background_color; 
    $source_data['button_alt_text_color'] = get_theme_mod( 'storefront_button_alt_text_color' ); 
    if(!empty($json_input->button_alt_text_color)) $source_data['button_alt_text_color']=$json_input->button_alt_text_color; 


    $post = get_post(84);
    $source_data['custom_css'] = $post->post_content;
    if(!is_null($json_input->custom_css)) $source_data['custom_css']=$json_input->custom_css; 
    
    //update theme data
    set_theme_mod('sellerlinx_icon_url',$source_data['icon_url']);
    set_theme_mod('sellerlinx_logo_url',$source_data['logo_url']);
    set_theme_mod('sellerlinx_product_border_size',$source_data['product_border_size']);
    set_theme_mod('sellerlinx_product_border_color','#'.$source_data['product_border_color']);
    set_theme_mod('storefront_layout_facebook_products_per_page',$source_data['facebook_products_per_page']);
    set_theme_mod('storefront_layout_products_per_page',$source_data['products_per_page']);
    set_theme_mod('storefront_layout_facebook_columns',$source_data['facebook_columns']);
    set_theme_mod('storefront_layout_columns',$source_data['columns']);

    set_theme_mod('background_color',$source_data['background_color']);
    set_theme_mod('sellerlinx_background_url',$source_data['background_url']);
    
    set_theme_mod('sellerlinx_content_background_color',$source_data['content_background_color']);
    set_theme_mod('sellerlinx_background_repeat',$source_data['background_repeat']);
    set_theme_mod('sellerlinx_background_size',$source_data['background_size']);
    set_theme_mod('sellerlinx_background_attachment',$source_data['background_attachment']);

    set_theme_mod('storefront_header_background_color','#'.$source_data['header_background_color']);
    set_theme_mod('storefront_header_text_color','#'.$source_data['header_text_color']);
    set_theme_mod('storefront_header_link_color','#'.$source_data['header_link_color']);

    set_theme_mod('storefront_footer_background_color','#'.$source_data['footer_background_color']);
    set_theme_mod('storefront_footer_heading_color','#'.$source_data['footer_heading_color']);
    set_theme_mod('storefront_footer_text_color','#'.$source_data['footer_text_color']);
    set_theme_mod('storefront_footer_link_color','#'.$source_data['footer_link_color']);

    set_theme_mod('storefront_button_background_color','#'.$source_data['button_background_color']);
    set_theme_mod('storefront_button_text_color','#'.$source_data['button_text_color']);
    set_theme_mod('storefront_button_alt_background_color','#'.$source_data['button_alt_background_color']);
    set_theme_mod('storefront_button_alt_text_color','#'.$source_data['button_alt_text_color']);
 

    $post->post_content = $source_data['custom_css'];
    wp_update_post($post);

    $customizer = new Storefront_Customizer();
    $customizer->set_storefront_style_theme_mods();

    $response = new WP_REST_Response($source_data);
    return $response;
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'theme', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_theme_get_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_theme_update_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );


/*
 * Settings REST 
 */


function sellerlinx_get_settings(  $data ) {
    
    $data = array();
    $data['blog_public'] = intval(get_option("blog_public"));

    $mm_options = get_option("wpmm_settings");
    $data['maintenance_mode'] = $mm_options['general']['status'];

    $data['manage_stock'] = get_option("woocommerce_manage_stock");
    $data['hold_stock_minutes'] = get_option("woocommerce_hold_stock_minutes");
    $data['notify_low_stock'] = get_option("woocommerce_notify_low_stock");
    $data['notify_no_stock'] = get_option("woocommerce_notify_no_stock");
    $data['stock_email_recipient'] = get_option("woocommerce_stock_email_recipient");
    $data['low_stock_amount'] = get_option("woocommerce_notify_low_stock_amount");
    $data['notify_no_stock'] = get_option("woocommerce_notify_no_stock_amount");
    $data['hide_out_of_stock_items'] = get_option("woocommerce_hide_out_of_stock_items");
    $data['stock_format'] = get_option("woocommerce_stock_format");
    $response = new WP_REST_Response($data);
    return $response;

}

function sellerlinx_update_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();
    $source_data['blog_public'] = intval(get_option("blog_public"));
    if(!is_null($json_input->blog_public)) $source_data['blog_public']=$json_input->blog_public;

    $mm_options = get_option("wpmm_settings");
    $source_data['maintenance_mode'] = $mm_options['general']['status'];
    if(!is_null($json_input->maintenance_mode)) $source_data['maintenance_mode']=$json_input->maintenance_mode;    
    
    $source_data['manage_stock'] = get_option("woocommerce_manage_stock");
    if(!is_null($json_input->manage_stock)) $source_data['manage_stock']=$json_input->manage_stock;
    $source_data['hold_stock_minutes'] = get_option("woocommerce_hold_stock_minutes");
    if(!is_null($json_input->hold_stock_minutes)) $source_data['hold_stock_minutes']=$json_input->hold_stock_minutes;
    $source_data['notify_low_stock'] = get_option("woocommerce_notify_low_stock");
    if(!is_null($json_input->notify_low_stock)) $source_data['notify_low_stock']=$json_input->notify_low_stock;
    $source_data['notify_no_stock'] = get_option("woocommerce_notify_no_stock");
    if(!is_null($json_input->notify_no_stock)) $source_data['notify_no_stock']=$json_input->notify_no_stock;
    $source_data['stock_email_recipient'] = get_option("woocommerce_stock_email_recipient");
    if(!is_null($json_input->stock_email_recipient)) $source_data['stock_email_recipient']=$json_input->stock_email_recipient;
    $source_data['low_stock_amount'] = get_option("woocommerce_notify_low_stock_amount");
    if(!is_null($json_input->low_stock_amount)) $source_data['low_stock_amount']=$json_input->low_stock_amount;
    $source_data['notify_no_stock'] = get_option("woocommerce_notify_no_stock_amount");
    if(!is_null($json_input->notify_no_stock)) $source_data['notify_no_stock']=$json_input->notify_no_stock;
    $source_data['hide_out_of_stock_items'] = get_option("woocommerce_hide_out_of_stock_items");
    if(!is_null($json_input->hide_out_of_stock_items)) $source_data['hide_out_of_stock_items']=$json_input->hide_out_of_stock_items;
    $source_data['stock_format'] = get_option("woocommerce_stock_format");
    if(!is_null($json_input->stock_format)) $source_data['stock_format']=$json_input->stock_format;

    //update theme data
    update_option('blog_public',$source_data['blog_public'],'yes');
    $mm_options['general']['status'] = $source_data['maintenance_mode'];
    update_option('wpmm_settings',$mm_options,'yes');

    update_option("woocommerce_manage_stock",$source_data['manage_stock'],'yes');
    update_option("woocommerce_hold_stock_minutes",$source_data['hold_stock_minutes'],'yes');
    update_option("woocommerce_notify_low_stock",$source_data['notify_low_stock'],'yes');
    update_option("woocommerce_notify_no_stock",$source_data['notify_no_stock'],'yes');
    update_option("woocommerce_stock_email_recipient",$source_data['stock_email_recipient'],'yes');
    update_option("woocommerce_notify_low_stock_amount",$source_data['low_stock_amount'],'yes');
    update_option("woocommerce_notify_no_stock_amount",$source_data['notify_no_stock'],'yes');
    update_option("woocommerce_hide_out_of_stock_items",$source_data['hide_out_of_stock_items'],'yes');
    update_option("woocommerce_stock_format",$source_data['stock_format'],'yes');

    $response = new WP_REST_Response($source_data);
    return $response;
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'settings', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );



/*
 * Email Settings REST 
 */

function sellerlinx_get_email_settings(  $data ) {
    $data = array();
    
    $data['mail_from'] = get_option("mail_from");
    $data['mail_from_name'] = get_option("mail_from_name");
    $data['smtp_host'] = get_option("smtp_host");
    $data['smtp_port'] = get_option("smtp_port");
    $data['smtp_user'] = get_option("smtp_user");
    $data['smtp_pass'] = base64_encode(get_option("smtp_pass"));
    $data['smtp_auth'] = get_option("smtp_auth");
    $data['smtp_ssl'] = get_option("smtp_ssl");

    $data['email_header_image'] = get_option("woocommerce_email_header_image");    
    $data['email_footer_text'] = get_option("woocommerce_email_footer_text");    
    $data['email_base_color'] = substr(get_option("woocommerce_email_base_color"),1);    
    $data['email_background_color'] = substr(get_option("woocommerce_email_background_color"),1);
    $data['email_body_background_color'] = substr(get_option("woocommerce_email_body_background_color"),1);
    $data['email_text_color'] = substr(get_option("woocommerce_email_text_color"),1); 

    $data['email_new_order'] = get_option("woocommerce_new_order_settings");
    $data['email_cancelled_order'] = get_option("woocommerce_cancelled_order_settings");
    $data['email_failed_order'] = get_option("woocommerce_failed_order_settings");
    $data['email_customer_on_hold_order'] = get_option("woocommerce_customer_on_hold_order_settings");
    $data['email_customer_processing_order'] = get_option("woocommerce_customer_processing_order_settings");
    $data['email_customer_completed_order'] = get_option("woocommerce_customer_completed_order_settings");
    $data['email_customer_refunded_order'] = get_option("woocommerce_customer_refunded_order_settings");
    $data['email_customer_invoice'] = get_option("woocommerce_customer_invoice_settings");
    $data['email_customer_note'] = get_option("woocommerce_customer_note_settings");
    $data['email_customer_reset_password'] = get_option("woocommerce_customer_reset_password_settings");
    $data['email_customer_new_account'] = get_option("woocommerce_customer_new_account_settings");

    


    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_email_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();
    $source_data['mail_from'] = get_option("mail_from");
    if(!is_null($json_input->mail_from)) $source_data['mail_from']=$json_input->mail_from;
    $source_data['mail_from_name'] = get_option("mail_from_name");
    if(!is_null($json_input->mail_from_name)) $source_data['mail_from_name']=$json_input->mail_from_name;    
    $source_data['smtp_host'] = get_option("smtp_host");
    if(!is_null($json_input->smtp_host)) $source_data['smtp_host']=$json_input->smtp_host;    
    $source_data['smtp_port'] = get_option("smtp_port");
    if(!is_null($json_input->smtp_port)) $source_data['smtp_port']=$json_input->smtp_port;
    $source_data['smtp_user'] = get_option("smtp_user");
    if(!is_null($json_input->smtp_user)) $source_data['smtp_user']=$json_input->smtp_user;
    $source_data['smtp_pass'] = get_option("smtp_pass");
    if(!is_null($json_input->smtp_pass)) $source_data['smtp_pass']=base64_decode($json_input->smtp_pass);
    $source_data['smtp_auth'] = get_option("smtp_auth");
    if(!is_null($json_input->smtp_auth)) $source_data['smtp_auth']=$json_input->smtp_auth;    
    $source_data['smtp_ssl'] = get_option("smtp_ssl");
    if(!is_null($json_input->smtp_ssl)) $source_data['smtp_ssl']=$json_input->smtp_ssl;    

    $source_data['email_header_image'] = get_option("woocommerce_email_header_image");
    if(!is_null($json_input->email_header_image)) $source_data['email_header_image']=$json_input->email_header_image; 
    $source_data['email_footer_text'] = get_option("woocommerce_email_footer_text");
    if(!is_null($json_input->email_footer_text)) $source_data['email_footer_text']=$json_input->email_footer_text; 
    $source_data['email_base_color'] = get_option("woocommerce_email_base_color");
    if(!is_null($json_input->email_base_color)) $source_data['email_base_color']=$json_input->email_base_color; 
    $source_data['email_background_color'] = get_option("woocommerce_email_background_color");
    if(!is_null($json_input->email_background_color)) $source_data['email_background_color']=$json_input->email_background_color;
    $source_data['email_body_background_color'] = get_option("woocommerce_email_body_background_color");
    if(!is_null($json_input->email_body_background_color)) $source_data['email_body_background_color']=$json_input->email_body_background_color;
    $source_data['email_text_color'] = get_option("woocommerce_email_text_color");
    if(!is_null($json_input->email_text_color)) $source_data['email_text_color']=$json_input->email_text_color;


    $source_data['email_new_order'] = get_option("woocommerce_new_order_settings");
    if(!is_null($json_input->email_new_order)) $source_data['email_new_order']=$json_input->email_new_order; 
    $source_data['email_cancelled_order'] = get_option("woocommerce_cancelled_order_settings");
    if(!is_null($json_input->email_cancelled_order)) $source_data['email_cancelled_order']=$json_input->email_cancelled_order; 
    $source_data['email_failed_order'] = get_option("woocommerce_failed_order_settings");
    if(!is_null($json_input->email_failed_order)) $source_data['email_failed_order']=$json_input->email_failed_order; 
    $source_data['email_customer_on_hold_order'] = get_option("woocommerce_customer_on_hold_order_settings");
    if(!is_null($json_input->email_customer_on_hold_order)) $source_data['email_customer_on_hold_order']=$json_input->email_customer_on_hold_order; 
    $source_data['email_customer_processing_order'] = get_option("woocommerce_customer_processing_order_settings");
    if(!is_null($json_input->email_customer_processing_order)) $source_data['email_customer_processing_order']=$json_input->email_customer_processing_order; 
    $source_data['email_customer_completed_order'] = get_option("woocommerce_customer_completed_order_settings");
    if(!is_null($json_input->email_customer_completed_order)) $source_data['email_customer_completed_order']=$json_input->email_customer_completed_order; 
    $source_data['email_customer_refunded_order'] = get_option("woocommerce_customer_refunded_order_settings");
    if(!is_null($json_input->email_customer_refunded_order)) $source_data['email_customer_refunded_order']=$json_input->email_customer_refunded_order; 
    $source_data['email_customer_invoice'] = get_option("woocommerce_customer_invoice_settings");
    if(!is_null($json_input->email_customer_invoice)) $source_data['email_customer_invoice']=$json_input->email_customer_invoice; 
    $source_data['email_customer_note'] = get_option("woocommerce_customer_note_settings");
    if(!is_null($json_input->email_customer_note)) $source_data['email_customer_note']=$json_input->email_customer_note; 
    $source_data['email_customer_reset_password'] = get_option("woocommerce_customer_reset_password_settings");
    if(!is_null($json_input->email_customer_reset_password)) $source_data['email_customer_reset_password']=$json_input->email_customer_reset_password; 
    $source_data['email_customer_new_account'] = get_option("woocommerce_customer_new_account_settings");
    if(!is_null($json_input->email_customer_new_account)) $source_data['email_customer_new_account']=$json_input->email_customer_new_account; 


    //update theme data
    update_option('mail_from',$source_data['mail_from'],'yes');
    update_option('mail_from_name',$source_data['mail_from_name'],'yes');
    update_option('smtp_host',$source_data['smtp_host'],'yes');
    update_option('smtp_port',$source_data['smtp_port'],'yes');
    update_option('smtp_user',$source_data['smtp_user'],'yes');
    update_option('smtp_pass',$source_data['smtp_pass'],'yes');
    update_option('smtp_auth',$source_data['smtp_auth'],'yes');
    update_option('smtp_ssl',$source_data['smtp_ssl'],'yes');

    update_option('woocommerce_email_header_image', $source_data['email_header_image'], 'yes');
    update_option('woocommerce_email_footer_text', $source_data['email_footer_text'], 'yes');
    update_option('woocommerce_email_base_color', '#'.$source_data['email_base_color'], 'yes');
    update_option('woocommerce_email_background_color', '#'.$source_data['email_background_color'], 'yes');
    update_option('woocommerce_email_body_background_color', '#'.$source_data['email_body_background_color'], 'yes');
    update_option('woocommerce_email_text_color', '#'.$source_data['email_text_color'], 'yes');

    update_option('woocommerce_new_order_settings',$source_data['email_new_order'],'yes');
    update_option('woocommerce_cancelled_order_settings',$source_data['email_cancelled_order'],'yes');
    update_option('woocommerce_failed_order_settings',$source_data['email_failed_order'],'yes');
    update_option('woocommerce_customer_on_hold_order_settings',$source_data['email_customer_on_hold_order'],'yes');
    update_option('woocommerce_customer_processing_order_settings',$source_data['email_customer_processing_order'],'yes');
    update_option('woocommerce_customer_completed_order_settings',$source_data['email_customer_completed_order'],'yes');
    update_option('woocommerce_customer_refunded_order_settings',$source_data['email_customer_refunded_order'],'yes');
    update_option('woocommerce_customer_invoice_settings',$source_data['email_customer_invoice'],'yes');
    update_option('woocommerce_customer_note_settings',$source_data['email_customer_note'],'yes');
    update_option('woocommerce_customer_reset_password_settings',$source_data['email_customer_reset_password'],'yes');
    update_option('woocommerce_customer_new_account_settings',$source_data['email_customer_new_account'],'yes');
    
    $response = new WP_REST_Response($source_data);
    return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'email', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_email_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_email_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );



/*
 * Tracking Settings REST 
 */
function sellerlinx_get_tracking_settings(  $data ) {
    $data = array();
    $google_tag_manager_options = get_option("gtm4wp-options");
    $facebook_pixel_options = get_option("pixel_your_site");
    $google_conversion_id_options = get_option("wgact_plugin_options_1");
    $google_conversion_label_options = get_option("wgact_plugin_options_2");
    $google_remarketing_options = get_option("wgdr_plugin_options");

    $data['google_analytics'] = get_theme_mod("sellerlinx_google_analytics");
    $data['google_remarketing_tag'] = $google_remarketing_options["conversion_id"];
    $data['google_adwords_conversion_id'] = $google_conversion_id_options['text_string'];
    $data['google_adwords_conversion_label'] = $google_conversion_label_options['text_string'];
    $data['google_tag_manager'] = $google_tag_manager_options['gtm-code'];
    $data['facebook_pixel'] = $facebook_pixel_options['general']['pixel_id'];
    
    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_tracking_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $google_tag_manager_options = get_option("gtm4wp-options");
    $facebook_pixel_options = get_option("pixel_your_site");
    $google_conversion_id_options = get_option("wgact_plugin_options_1");
    $google_conversion_label_options = get_option("wgact_plugin_options_2");
    $google_remarketing_options = get_option("wgdr_plugin_options");

    $source_data = array();
    $source_data['google_analytics'] = get_theme_mod("sellerlinx_google_analytics");
    if(!is_null($json_input->google_analytics)) $source_data['google_analytics']=$json_input->google_analytics;
    $source_data['google_remarketing_tag'] = $google_remarketing_options["conversion_id"];
    if(!is_null($json_input->google_remarketing_tag)) $source_data['google_remarketing_tag']=$json_input->google_remarketing_tag;
    $source_data['google_adwords_conversion_id'] = $google_conversion_id_options['text_string'];
    if(!is_null($json_input->google_adwords_conversion_id)) $source_data['google_adwords_conversion_id']=$json_input->google_adwords_conversion_id;
    $source_data['google_adwords_conversion_label'] = $google_conversion_label_options['text_string'];
    if(!is_null($json_input->google_adwords_conversion_label)) $source_data['google_adwords_conversion_label']=$json_input->google_adwords_conversion_label;
    $source_data['google_tag_manager'] = $google_tag_manager_options['gtm-code'];
    if(!is_null($json_input->google_tag_manager)) $source_data['google_tag_manager']=$json_input->google_tag_manager;
    $source_data['facebook_pixel'] = $facebook_pixel_options['general']['pixel_id'];
    if(!is_null($json_input->facebook_pixel)) $source_data['facebook_pixel']=$json_input->facebook_pixel;
    

    //update theme data
    set_theme_mod('sellerlinx_google_analytics',$source_data['google_analytics']);

    $google_remarketing_options["conversion_id"] = $source_data['google_remarketing_tag'];
    update_option('wgdr_plugin_options', $google_remarketing_options, yes);

    $google_conversion_id_options['text_string'] = $source_data['google_adwords_conversion_id'];
    update_option('wgact_plugin_options_1', $google_conversion_id_options, yes);
    $google_conversion_label_options['text_string'] = $source_data['google_adwords_conversion_label'];
    update_option('wgact_plugin_options_2', $google_conversion_label_options, yes);

    $google_tag_manager_options['gtm-code'] = $source_data['google_tag_manager'];
    update_option('gtm4wp-options', $google_tag_manager_options, yes);
    $facebook_pixel_options['general']['pixel_id'] = $source_data['facebook_pixel'];
    update_option('pixel_your_site', $facebook_pixel_options, yes);
    
    set_theme_mod('sellerlinx_facebook_pixel',$source_data['facebook_pixel']);
    
    
    $response = new WP_REST_Response($source_data);
    return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'tracking', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_tracking_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_tracking_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );




/*
 * Social Link Settings REST 
 */
function sellerlinx_get_social_links_settings(  $data ) {
    $data = array();
    
    $data['links'] = array();

    $link_options = get_option("widget_sellerlinx-social-link-widget");
    
    $i=0;
    foreach($link_options as $widget):
        if(!is_array($widget)||count($widget)==0) continue;
        $data['links'][$i] = array();
        $data['links'][$i]['title'] = $widget['title'];
        $data['links'][$i]['url'] = $widget['url'];
        $data['links'][$i]['icon_color'] = substr($widget['icon_color'],1);
        $data['links'][$i]['text_color'] = substr($widget['text_color'],1);
        $i++;
    endforeach;
    
    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_social_links_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();
    
    $source_data['links'] = $json_input->links;
    
    $sidebars_widgets_options = get_option("sidebars_widgets");
    $sidebars_widgets_options['sidebar-social-links'] = array();

    $banners = array();
    for($i=0;$i<count($source_data['links']);$i++){
        $sidebars_widgets_options['sidebar-social-links'][] = 'sellerlinx-social-link-widget-'.$i;
        $link = array();
        $link['title'] = $source_data['links'][$i]->title;
        $link['url'] = $source_data['links'][$i]->url;
        $link['icon_color'] = '#'.$source_data['links'][$i]->icon_color;
        $link['text_color'] = '#'.$source_data['links'][$i]->text_color;
        
        $links[] = $link;
    }

    update_option('widget_sellerlinx-social-link-widget', $links, yes);
    update_option('sidebars_widgets', $sidebars_widgets_options, yes);

    $response = new WP_REST_Response($source_data);
    return $response;
}



add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'social-links', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_social_links_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_social_links_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );


/*
 * Social Login Settings REST 
 */
function sellerlinx_get_social_settings(  $data ) {
    $data = array();
    $social_options = get_option("wc_social_login_facebook_settings");
    $data['facebook_enabled'] = $social_options['enabled'];
    $data['facebook_client_id'] = $social_options['id'];
    $data['facebook_client_secret'] = $social_options['secret'];

    $social_options = get_option("wc_social_login_google_settings");
    $data['google_enabled'] = $social_options['enabled'];
    $data['google_client_id'] = $social_options['id'];
    $data['google_client_secret'] = $social_options['secret'];
    
    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_social_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();

    $social_options = get_option("wc_social_login_facebook_settings");
    $source_data['facebook_enabled'] = $social_options['enabled'];
    if(!is_null($json_input->facebook_enabled)) $source_data['facebook_enabled']=$json_input->facebook_enabled;
    $source_data['facebook_client_id'] = $social_options['id'];
    if(!is_null($json_input->facebook_client_id)) $source_data['facebook_client_id']=$json_input->facebook_client_id;
    $source_data['facebook_client_secret'] = $social_options['secret'];
    if(!is_null($json_input->facebook_client_secret)) $source_data['facebook_client_secret']=$json_input->facebook_client_secret;


    //update theme data
    $social_options['enabled'] = $source_data['facebook_enabled'];
    $social_options['id'] = $source_data['facebook_client_id'];
    $social_options['secret'] = $source_data['facebook_client_secret'];
    update_option('wc_social_login_facebook_settings',$social_options,'yes');

    $social_options = get_option("wc_social_login_google_settings");
    $source_data['google_enabled'] = $social_options['enabled'];
    if(!is_null($json_input->google_enabled)) $source_data['google_enabled']=$json_input->google_enabled;
    $source_data['google_client_id'] = $social_options['id'];
    if(!is_null($json_input->google_client_id)) $source_data['google_client_id']=$json_input->google_client_id;
    $source_data['google_client_secret'] = $social_options['secret'];
    if(!is_null($json_input->google_client_secret)) $source_data['google_client_secret']=$json_input->google_client_secret;

    $social_options['enabled'] = $source_data['google_enabled'];
    $social_options['id'] = $source_data['google_client_id'];
    $social_options['secret'] = $source_data['google_client_secret'];
    update_option('wc_social_login_google_settings',$social_options,'yes');
    
    
    
    $response = new WP_REST_Response($source_data);
    return $response;
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'social', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_social_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_social_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );



/*
 * Language Settings REST 
 */

function sellerlinx_get_language_settings(  $data ) {
    $data = array();
    $lang_options = get_option("qtranslate_enabled_languages");

    $data['supported']=$lang_options;

    $lang_options = get_option("qtranslate_default_language");
    $data['default']=$lang_options; 
    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_language_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));
    $source_data = array();
  
    if(!is_null($json_input->supported)) $source_data['supported']=$json_input->supported;

    update_option('qtranslate_enabled_languages',$source_data['supported'],'yes');

    $lang_options = get_option("qtranslate_default_language");
    $source_data['default']=$lang_options; 
    if(!is_null($json_input->default)) $source_data['default']=$json_input->default;
    
    //update theme data
    update_option('qtranslate_default_language',$source_data['default'],'yes');

    $response = new WP_REST_Response($source_data);
    return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'languages', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_language_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_language_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );




/*
 * Payment Settings REST 
 */


function sellerlinx_get_payment_settings(  $data ) {
    $data = array();
    $currency_options = get_option("woocommerce_currency");

    $data['currency']=$currency_options;

    $cod_options = get_option("woocommerce_cod_settings");
    $data['cod_enabled']=$cod_options['enabled'];

    $cod_options = get_option("woocommerce_paypal_settings");
    $data['paypal_enabled']=$cod_options['enabled'];
    $data['paypal_email']=$cod_options['email'];

    $cod_options = get_option("woocommerce_innovext_allpay_aio_settings");
    $data['allpay_enabled']=$cod_options['enabled'];
    $data['allpay_id']=$cod_options['MerchantID'];
    $data['allpay_hash_key']=$cod_options['hash_key'];
    $data['allpay_hash_iv']=$cod_options['hash_iv'];
    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_payment_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();
    
    
    $source_data['currency'] = get_option("woocommerce_currency");
    if(!is_null($json_input->currency)) $source_data['currency']=$json_input->currency;

    $cod_options = get_option("woocommerce_cod_settings");
    $source_data['cod_enabled'] = $cod_options['enabled'];
    if(!is_null($json_input->cod_enabled)) $source_data['cod_enabled']=$json_input->cod_enabled;
    
    $paypal_options = get_option("woocommerce_paypal_settings");
    $source_data['paypal_enabled'] = $paypal_options['enabled'];
    if(!is_null($json_input->paypal_enabled)) $source_data['paypal_enabled']=$json_input->paypal_enabled;
    $source_data['paypal_email'] = $paypal_options['email'];
    if(!is_null($json_input->paypal_email)) $source_data['paypal_email']=$json_input->paypal_email;


    $allpay_options = get_option("woocommerce_innovext_allpay_aio_settings");
    $source_data['allpay_enabled'] = $allpay_options['enabled'];
    if(!is_null($json_input->allpay_enabled)) $source_data['allpay_enabled']=$json_input->allpay_enabled;
    $source_data['allpay_id'] = $allpay_options['MerchantID'];
    if(!is_null($json_input->allpay_id)) $source_data['allpay_id']=$json_input->allpay_id;
    $source_data['allpay_hash_key'] = $allpay_options['hash_key'];
    if(!is_null($json_input->allpay_hash_key)) $source_data['allpay_hash_key']=$json_input->allpay_hash_key;
    $source_data['allpay_hash_iv'] = $allpay_options['hash_iv'];
    if(!is_null($json_input->allpay_hash_iv)) $source_data['allpay_hash_iv']=$json_input->allpay_hash_iv;


    update_option('woocommerce_currency',$source_data['currency'],'yes');

    $cod_options['enabled'] = $source_data['cod_enabled'];
    update_option('woocommerce_cod_settings',$cod_options,'yes');
    $paypal_options['enabled'] = $source_data['paypal_enabled'];
    $paypal_options['email'] = $source_data['paypal_email'];
    update_option('woocommerce_paypal_settings',$paypal_options,'yes');

    $allpay_options['enabled'] = $source_data['allpay_enabled'];
    $allpay_options['MerchantID'] = $source_data['allpay_id'];
    $allpay_options['hash_key'] = $source_data['allpay_hash_key'];
    $allpay_options['hash_iv'] = $source_data['allpay_hash_iv'];
    update_option('woocommerce_innovext_allpay_aio_settings',$allpay_options,'yes');


    $response = new WP_REST_Response($source_data);
    return $response;
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'payment', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_payment_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_payment_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );


/*
 * Banner Settings REST 
 */

function sellerlinx_get_banner_settings(  $data ) {
    $data = array();

    $data['height'] = get_theme_mod("sellerlinx_banner_height");
    $data['mobile_height'] = get_theme_mod("sellerlinx_mobile_banner_height");

    $data['configuration'] = get_theme_mod( 'sellerlinx_banner_configuration' ); 
    $data['transition'] = get_theme_mod( 'sellerlinx_banner_transition' ); 
    $data['transition_duration'] = get_theme_mod( 'sellerlinx_banner_transition_duration' ); 

    $data['banners'] = array();

    $banner_options = get_option("widget_sellerlinx-banner-widget");
    
    $i=0;
    foreach($banner_options as $widget):
        if(!is_array($widget)||count($widget)==0) continue;
        $data['banners'][$i] = array();
        $data['banners'][$i]['title'] = $widget['title'];
        $data['banners'][$i]['content'] = $widget['content'];
        $data['banners'][$i]['image_url'] = $widget['image_url'];
        $data['banners'][$i]['link_url'] = $widget['link_url'];
        $data['banners'][$i]['video_url'] = $widget['video_url'];
        $data['banners'][$i]['background_color'] = substr($widget['background_color'],1);
        $data['banners'][$i]['overlay_color'] = substr($widget['overlay_color'],1);
        $data['banners'][$i]['overlay_opacity'] = $widget['overlay_opacity'];
        $data['banners'][$i]['anchor_position'] = $widget['anchor_position'];
        $data['banners'][$i]['size'] = $widget['size'];
        $i++;
    endforeach;

    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_banner_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();
    
    $source_data['height'] = get_theme_mod("sellerlinx_banner_height");
    if(!is_null($json_input->height)) $source_data['height']=$json_input->height;
    $source_data['mobile_height'] = get_theme_mod("sellerlinx_mobile_banner_height");
    if(!is_null($json_input->mobile_height)) $source_data['mobile_height']=$json_input->mobile_height;
    $source_data['configuration'] = get_theme_mod( 'sellerlinx_banner_configuration' ); 
    if(!is_null($json_input->configuration)) $source_data['configuration']=$json_input->configuration; 
    $source_data['transition'] = get_theme_mod( 'sellerlinx_banner_transition' ); 
    if(!is_null($json_input->transition)) $source_data['transition']=$json_input->transition; 
    $source_data['transition_duration'] = get_theme_mod( 'sellerlinx_banner_transition_duration' ); 
    if(!is_null($json_input->transition_duration)) $source_data['transition_duration']=$json_input->transition_duration; 

    $source_data['banners'] = $json_input->banners;
    
    set_theme_mod("sellerlinx_banner_height", $source_data['height']);
    set_theme_mod("sellerlinx_mobile_banner_height", $source_data['mobile_height']);
    set_theme_mod("sellerlinx_banner_configuration", $source_data['configuration']);
    set_theme_mod("sellerlinx_banner_transition", $source_data['transition']);
    set_theme_mod("sellerlinx_banner_transition_duration", $source_data['transition_duration']);

    
    $sidebars_widgets_options = get_option("sidebars_widgets");
    $sidebars_widgets_options['sidebar-banner'] = array();

    $banners = array();
    for($i=0;$i<count($source_data['banners']);$i++){
        $sidebars_widgets_options['sidebar-banner'][] = 'sellerlinx-banner-widget-'.$i;
        $banner = array();
        $banner['title'] = $source_data['banners'][$i]->title;
        $banner['content'] = $source_data['banners'][$i]->content;
        $banner['image_url'] = $source_data['banners'][$i]->image_url;
        $banner['video_url'] = $source_data['banners'][$i]->video_url;
        $banner['link_url'] = $source_data['banners'][$i]->link_url;
        $banner['background_color'] = '#'.$source_data['banners'][$i]->background_color;
        $banner['overlay_color'] = '#'.$source_data['banners'][$i]->overlay_color;
        $banner['overlay_opacity'] = $source_data['banners'][$i]->overlay_opacity;
        $banner['anchor_position'] = $source_data['banners'][$i]->anchor_position;
        $banner['size'] = $source_data['banners'][$i]->size;
        
        $banners[] = $banner;
    }

    update_option('widget_sellerlinx-banner-widget', $banners, yes);
    update_option('sidebars_widgets', $sidebars_widgets_options, yes);

    $response = new WP_REST_Response($source_data);
    return $response;
}




add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'banners', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_banner_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_banner_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
} );




/*
 * Shipping REST 
 */


function sellerlinx_get_shipping_settings(  $request ) {

	global $wpdb;
    $results = $wpdb->get_results( 'SELECT zone_id, instance_id, method_id, method_order, is_enabled FROM '.$wpdb->prefix.'woocommerce_shipping_zone_methods' );
	
	$data = array();

    $data['shipping_zone_methods'] = array();

    $i = 0;
    foreach ( $results as $shipping_zone ) 
    {
    	$data['shipping_zone_methods'][$i] = array();
    	$data['shipping_zone_methods'][$i]['zone_id'] = $shipping_zone->zone_id;
    	$data['shipping_zone_methods'][$i]['instance_id'] = $shipping_zone->instance_id;
    	$data['shipping_zone_methods'][$i]['method_id'] = $shipping_zone->method_id;
    	$data['shipping_zone_methods'][$i]['method_order'] = $shipping_zone->method_order;
    	$data['shipping_zone_methods'][$i]['is_enabled'] = $shipping_zone->is_enabled;
        $data['shipping_zone_methods'][$i]['options'] = get_option('woocommerce_'.$shipping_zone->method_id.'_'.$shipping_zone->instance_id.'_settings');
        $i++;
    }

    $response = new WP_REST_Response($data);
    return $response;
  
}
function sellerlinx_update_shipping_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();

    //nuke all methods...

    //clear existing settings
    global $wpdb;
    $results = $wpdb->get_results( 'SELECT zone_id, instance_id, method_id, method_order, is_enabled FROM '.$wpdb->prefix.'woocommerce_shipping_zone_methods' );
    foreach ( $results as $shipping_zone ) 
    {
        delete_option('woocommerce_'.$shipping_zone->method_id.'_'.$shipping_zone->instance_id.'_settings');
    }

    //delete all methods
    $wpdb->get_results( 'TRUNCATE '.$wpdb->prefix.'woocommerce_shipping_zone_methods' );
    
    $source_data['shipping_zone_methods'] = $json_input->shipping_zone_methods;
    
    for($i=0;$i<count($source_data['shipping_zone_methods']);$i++){
        $shipping_zone_method = $source_data['shipping_zone_methods'][$i];
        $results = $wpdb->get_results( 'INSERT INTO '.$wpdb->prefix.'woocommerce_shipping_zone_methods (zone_id, instance_id, method_id, method_order, is_enabled) values ('.$shipping_zone_method->zone_id.','.$shipping_zone_method->instance_id.',"'.$shipping_zone_method->method_id.'",'.$shipping_zone_method->method_order.','.$shipping_zone_method->is_enabled.')' );

        $options =  array();

        
        switch($shipping_zone_method->method_id){
            case 'flat_rate':
                $options['title'] = $shipping_zone_method->options->title;
                $options['tax_status'] = $shipping_zone_method->options->tax_status;
                $options['cost'] = $shipping_zone_method->options->cost;        
                break;
            case 'free_shipping':
                $options['title'] = $shipping_zone_method->options->title;
                $options['requires'] = $shipping_zone_method->options->requires;
                $options['min_amount'] = $shipping_zone_method->options->min_amount;        
                break;
            case 'local_pickup':
                $options['title'] = $shipping_zone_method->options->title;
                $options['tax_status'] = $shipping_zone_method->options->tax_status;
                $options['cost'] = $shipping_zone_method->options->cost;        
                break;
            default:
                break;
        }


        add_option('woocommerce_'.$shipping_zone_method->method_id.'_'.$shipping_zone_method->instance_id.'_settings', $options , yes);
    }


    $response = new WP_REST_Response($source_data);
    return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'shipping', array(
     array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_shipping_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_shipping_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
});




/*
 * Products REST 
 */


function sellerlinx_get_product_settings(  $request ) {

    
    $data = array();

    $params = $request->get_params();

    $data['product_id'] = $params['id'];
    $data['product_image_url'] = get_post_meta(intval($params['id']),"image_url");
    $data['product_thumbnail_url'] = get_post_meta(intval($params['id']),"thumbnail_url");

    $response = new WP_REST_Response($data);
    return $response;
  
}
function sellerlinx_update_product_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();

   
    
    $source_data['shipping_zone_methods'] = $json_input->shipping_zone_methods;
    
  


    $response = new WP_REST_Response($source_data);
    return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'products/(?P<id>[\d]+)', array(
     array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_product_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_product_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
});



/*
 * Menu REST 
 */


function sellerlinx_get_menu_settings(  $request ) {

    global $wpdb;
    
    
    $data = array();

    $custom_menu = get_theme_mod('sellerlinx_custom_main_menu');
    $use_custom = 0;
    if($custom_menu=="custom") $use_custom = 1;
    $data['use_custom'] = $use_custom;

    $data['custom_menu'] = array();

    $args = array('echo'=>FALSE);
    $menu = wp_get_nav_menu_items('Custom Main Menu',$args);
    $i = 0;
    $data['custom_menu'] = $menu;
    

    $response = new WP_REST_Response($data);
    return $response;
  
}
function sellerlinx_update_menu_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();

    //nuke all methods...


    //clear existing settings
    global $wpdb;

    //get menu ID
    $results = $wpdb->get_results( 'SELECT term_id FROM '.$wpdb->prefix.'terms WHERE name = "Custom Main Menu"' );

    $menuID = -1;
    foreach ( $results as $result ) $menuID = $result->term_id;

    //get all menu item post IDs
    $results = $wpdb->get_results( 'SELECT object_id FROM '.$wpdb->prefix.'term_relationships WHERE term_taxonomy_id = '.$menuID );
    $menuItemPostID = array();
    foreach ( $results as $result ) $menuItemPostID[] = $result->object_id;
    
    $custom_menu = get_theme_mod('sellerlinx_custom_main_menu');
    $use_custom = 0;
    if($custom_menu=="custom") $use_custom = 1;
    $source_data['use_custom'] = $use_custom;
    if(!is_null($json_input->use_custom)) $source_data['use_custom']=$json_input->use_custom;

    
    //if($use_custom==1){
        //clear out existing menu items
        for($i=0;$i<count($menuItemPostID);$i++){
            //delete post metas    
            $results = $wpdb->get_results('DELETE FROM '.$wpdb->prefix.'postmeta WHERE post_id='.$menuItemPostID[$i]);

            //delete post
            $results = $wpdb->get_results('DELETE FROM '.$wpdb->prefix.'posts WHERE ID='.$menuItemPostID[$i]);
        }

        //delete relationships
        $results = $wpdb->get_results('DELETE FROM '.$wpdb->prefix.'term_relationships WHERE term_taxonomy_id = '.$menuID);


        
        $results = $wpdb->get_results("SELECT Auto_increment FROM information_schema.tables WHERE table_name='".$wpdb->prefix."posts' AND table_schema='".DB_NAME."'");
        $nextID;
        foreach ( $results as $result) 
        {
            $nextID = $result->Auto_increment;
        }
        

        //reorder and insert menu items as new posts
        $originalID = array();

        for($i=0;$i<count($json_input->custom_menu);$i++){
            $originalID[$i] = $json_input->custom_menu[$i]->ID;
        }

        for($i=0;$i<count($json_input->custom_menu);$i++){
            $sql = 'INSERT INTO '.$wpdb->prefix.'posts ( `ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES ';

            $uid = $nextID+$i;
            $now = date('Y-m-d H:i:s');
            if($json_input->custom_menu[$i]->type=="custom"){
                //custom link
                
                $sql.= "(".$uid.', 1,';
                $sql.= "'".$now."', '".$now."','',";
                $sql.= "'".$json_input->custom_menu[$i]->post_title."',";     //title
                $sql.= "'', 'publish', 'closed', 'closed', '', ";
                $sql.= "'".$json_input->custom_menu[$i]->post_name."',";     //slug
                $sql.= "'', '', '".$now."', '".$now."', '', 0, ";
                $sql.= "'".site_url( '/', 'https' )."?p=".$uid."', ";      //url
                $sql.= ($i+1).", ";        //order 
                $sql.= "'nav_menu_item', '', 0);";
                $results = $wpdb->get_results($sql);


                //associate posts with menu
                $sql = 'INSERT INTO `'.$wpdb->prefix.'term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES ';
                $sql.= '('.$uid.', '.$menuID.', 0);';
                $results = $wpdb->get_results($sql);


                //store additional post metas
                $sql = 'INSERT INTO `'.$wpdb->prefix.'postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES ';
                $sql.= "(".$uid.", '_menu_item_type', 'custom'),";
                $sql.= "(".$uid.", '_menu_item_menu_item_parent', '";

                $menu_item_parent = 0;
                for($j=0;$j<count($originalID);$j++){
                    if($json_input->custom_menu[$i]->menu_item_parent==$originalID[$j]){
                        //parent found
                        $menu_item_parent = $nextID+$j;
                        break;
                    }
                }
                $sql.= $menu_item_parent; 
                
                $sql.="'),";
                $sql.= "(".$uid.", '_menu_item_object_id', '".$uid."'),";   
                $sql.= "(".$uid.", '_menu_item_object', 'custom'),";
                $sql.= "(".$uid.", '_menu_item_target', '".$json_input->custom_menu[$i]->target."'),";
                $sql.= "(".$uid.", '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),";
                $sql.= "(".$uid.", '_menu_item_xfn', ''),";
                $sql.= "(".$uid.", '_menu_item_url', '".$json_input->custom_menu[$i]->url."');";
                $results = $wpdb->get_results($sql);


            }
            else if($json_input->custom_menu[$i]->type=="taxonomy"){
                $sql.= "(".$uid.', 1,';
                $sql.= "'".$now."', '".$now."', ' ', ";
                $sql.= "'".$json_input->custom_menu[$i]->post_title."', '', 'publish', 'closed', 'closed', '', ";
                $sql.= "'".$uid."', ";
                $sql.= "'', '', '".$now."', '".$now."', '', 0, ";
                //$sql.= "'".site_url( '/', 'https' )."?p=".$uid."', ";      //url
                $sql.= "'".$json_input->custom_menu[$i]->url."', ";      //url
                $sql.= ($i+1).", ";        //order 
                $sql.= "'nav_menu_item', '', 0);";
                $results = $wpdb->get_results($sql);

                //associate posts with menu
                $sql = 'INSERT INTO `'.$wpdb->prefix.'term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES ';
                $sql.= '('.$uid.', '.$menuID.', 0);';
                $results = $wpdb->get_results($sql);

                //store additional post metas
                $sql = 'INSERT INTO `'.$wpdb->prefix.'postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES ';
                $sql.= "(".$uid.", '_menu_item_type', 'taxonomy'),";
                $sql.= "(".$uid.", '_menu_item_menu_item_parent', '";

                $menu_item_parent = 0;
                for($j=0;$j<count($originalID);$j++){
                    if($json_input->custom_menu[$i]->menu_item_parent==$originalID[$j]){
                        //parent found
                        $menu_item_parent = $nextID+$j;
                        break;
                    }
                }
                $sql.= $menu_item_parent; 
                $sql.="'),";
                $sql.= "(".$uid.", '_menu_item_object_id', '".$json_input->custom_menu[$i]->object_id."'),";
                $sql.= "(".$uid.", '_menu_item_object', 'product_cat'),";
                $sql.= "(".$uid.", '_menu_item_target', '".$json_input->custom_menu[$i]->target."'),";
                $sql.= "(".$uid.", '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),";
                $sql.= "(".$uid.", '_menu_item_xfn', ''),";
                $sql.= "(".$uid.", '_menu_item_url', '".$json_input->custom_menu[$i]->url."');";
                $results = $wpdb->get_results($sql);
            }
            else if($json_input->custom_menu[$i]->type=="post_type"){
                $sql.= "(".$uid.', 1,';
                $sql.= "'".$now."', '".$now."', ' ', ";
                $sql.= "'".$json_input->custom_menu[$i]->post_title."', '', 'publish', 'closed', 'closed', '', ";
                $sql.= "'".$uid."', ";
                $sql.= "'', '', '".$now."', '".$now."', '', 0, ";
                $sql.= "'".site_url( '/', 'https' )."?p=".$uid."', ";      //url
                //$sql.= "'".$json_input->custom_menu[$i]->url."', ";      //url
                $sql.= ($i+1).", ";        //order 
                $sql.= "'nav_menu_item', '', 0);";
                $results = $wpdb->get_results($sql);

                //associate posts with menu
                $sql = 'INSERT INTO `'.$wpdb->prefix.'term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES ';
                $sql.= '('.$uid.', '.$menuID.', 0);';
                $results = $wpdb->get_results($sql);

                //store additional post metas
                $sql = 'INSERT INTO `'.$wpdb->prefix.'postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES ';
                $sql.= "(".$uid.", '_menu_item_type', 'post_type'),";
                $sql.= "(".$uid.", '_menu_item_menu_item_parent', '";

                $menu_item_parent = 0;
                for($j=0;$j<count($originalID);$j++){
                    if($json_input->custom_menu[$i]->menu_item_parent==$originalID[$j]){
                        //parent found
                        $menu_item_parent = $nextID+$j;
                        break;
                    }
                }
                $sql.= $menu_item_parent; 
                $sql.="'),";
                $sql.= "(".$uid.", '_menu_item_object_id', '".$json_input->custom_menu[$i]->object_id."'),";
                $sql.= "(".$uid.", '_menu_item_object', 'page'),";
                $sql.= "(".$uid.", '_menu_item_target', '".$json_input->custom_menu[$i]->target."'),";
                $sql.= "(".$uid.", '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),";
                $sql.= "(".$uid.", '_menu_item_xfn', ''),";
                $sql.= "(".$uid.", '_menu_item_url', '".$json_input->custom_menu[$i]->url."');";
                $results = $wpdb->get_results($sql);
            }

        }
    //}
    

    if($source_data['use_custom']=="1"){
        set_theme_mod('sellerlinx_custom_main_menu',"custom");
    }
    else{
        set_theme_mod('sellerlinx_custom_main_menu',"default");   
    }

    $source_data['custom_menu'] = array();

    $args = array('echo'=>FALSE);
    $menu = wp_get_nav_menu_items('Custom Main Menu',$args);
    $i = 0;
    $source_data['custom_menu'] = $menu;


    $response = new WP_REST_Response($source_data);
    return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'menus', array(
     array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_menu_settings',
        'permission_callback' => 'sellerlinx_auth',
      ),
      array(
        'methods' => 'POST',
        'callback' => 'sellerlinx_update_menu_settings',
        'permission_callback' => 'sellerlinx_auth',
      )
    )
   );
  
});






/*
 * Keys REST 
 */


function sellerlinx_get_keys(  $request ) {
    
    $headers = getallheaders();

    if(empty($headers['Authorization'])&&empty($headers['authorization'])) return false;
    
    $auth;
    
    if(!empty($headers['Authorization'])) $auth = $headers['Authorization'];
    else if(!empty($headers['authorization'])) $auth = $headers['authorization'];

    $auth  = explode(" ",$auth);
    $credentials = explode(":",base64_decode($auth[1]));
    $username = $credentials[0];
    $user = get_user_by('slug',$username);
    $data = array(); 
    global $wpdb;

    
    $results = $wpdb->get_results( 'SELECT consumer_key, consumer_secret FROM '.$wpdb->prefix.'woocommerce_api_keys WHERE user_id = '.$user->ID.' AND permissions = "read_write"' );
    
    foreach ( $results as $woo_key ) 
    {
        $consumer_actual_secret = $woo_key->consumer_secret;
        $consumer_key_encrypted = $woo_key->consumer_key;
    }

    $consumer_secret = get_theme_mod('sellerlinx_woo_consumer_secret');
    $consumer_key = get_theme_mod('sellerlinx_woo_consumer_key');

    $data['username'] = $username;
    $data['userID'] = $user->ID;
    
    $data['woocommerce_consumer_key']=$consumer_key;
    $data['woocommerce_consumer_secret']=$consumer_actual_secret;
    $data['woocommerce_actual_consumer_secret']=$consumer_secret;
    $data['generated']= wc_api_hash( sanitize_text_field( $consumer_key ) );
    $data['encrypted']= $consumer_key_encrypted;

    $response = new WP_REST_Response($data);
    return $response;
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'keys', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_keys',
        'permission_callback' => 'sellerlinx_auth',
      ),
    )
   );
  
});

?>