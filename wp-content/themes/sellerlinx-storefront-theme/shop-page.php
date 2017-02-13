<?php
/**
 * Template Name: Shop Page
 *
 * @package storefront
 */

include_once("facebook-includes.php");


global $fb;
global $app_data;
global $isFacebookPortal;

global $_SESSION;

/*
echo 'POST<br/>';
print_r($_POST);
echo '<br/>SR<br/>';
*/
//print_r($signedRequest);


if(!empty($app_data)){
	//header("location:".$app_data);
}


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post();

				do_action( 'storefront_page_before' );

				//get_template_part( 'content', 'shop' );

				//build columns based upon saved parameters
				$shortcode = '[recent_products per_page="';
				if($isFacebookPortal)  get_theme_mod( 'storefront_layout_facebook_products_per_page' );
				else $shortcode.= get_theme_mod( 'storefront_layout_products_per_page' );
				$shortcode.= '" columns="';

				if($isFacebookPortal) $shortcode.= get_theme_mod( 'storefront_layout_facebook_columns' );
				else $shortcode.= get_theme_mod( 'storefront_layout_columns' );
				$shortcode.= '"]';

				echo apply_filters( 'the_content',$shortcode);
				the_content();


				/**
				 * Functions hooked in to storefront_page_after action
				 *
				 * @hooked storefront_display_comments - 10
				 */
				do_action( 'storefront_page_after' );

			endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//do_action( 'storefront_sidebar' );
get_footer();
