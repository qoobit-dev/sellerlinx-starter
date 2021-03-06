<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();

header_remove("X-Frame-Options");
include_once("facebook-includes.php");

global $isFacebookPortal;
if(isset($_SESSION['access_method'])) $isFacebookTemplate = ($_SESSION['access_method'] == FACEBOOK_TEMPLATE);
else $isFacebookTemplate = false;

//print_r($_SESSION);
//echo session_id();

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php do_action( 'sellerlinx_meta' ); ?>
<?php wp_head(); ?>
</head>
<?php do_action( 'before_body' ); ?>
<body <?php body_class(); ?>>
<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>
<?php do_action( 'after_body' ); ?>
<div id="page" class="hfeed site">
	<?php
	do_action( 'storefront_before_header' ); ?>
	<header id="masthead" class="site-header<?php if($isFacebookTemplate): ?> facebook-site-header<?php endif;?>" role="banner" style="<?php storefront_header_styles(); ?>">
		<div class="col-full">
	<?php 
	if(!$isFacebookTemplate):
		/**
		 * Functions hooked into storefront_header action
		 *
		 * @hooked storefront_skip_links                       - 0
		 * @hooked storefront_social_icons                     - 10
		 * @hooked storefront_site_branding                    - 20
		 * @hooked storefront_secondary_navigation             - 30
		 * @hooked storefront_product_search                   - 40
		 * @hooked storefront_primary_navigation_wrapper       - 42
		 * @hooked storefront_primary_navigation               - 50
		 * @hooked storefront_header_cart                      - 60
		 * @hooked storefront_primary_navigation_wrapper_close - 68
		 */
		
			do_action( 'storefront_header' ); 

	else:

		/**
		 * Functions hooked into storefront_facebook_header action
		 *
		 */
			do_action( 'storefront_facebook_header' ); 

	endif;?>

		</div>
	</header><!-- #masthead -->
	
	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); 

	if(is_front_page()){
		do_action( 'sellerlinx_home_banners' ); 
	}


	?>


	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		/**
		 * Functions hooked in to storefront_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'storefront_content_top' );
