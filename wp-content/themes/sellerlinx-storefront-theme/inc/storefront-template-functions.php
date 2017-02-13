<?php
/**
 * Storefront template functions.
 *
 * @package storefront
 */

if ( ! function_exists( 'storefront_facebook_site_branding' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_facebook_site_branding() {
		?>

		<div class="site-branding">
			<?php storefront_site_title_or_logo(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'storefront_facebook_secondary_navigation' ) ) {
	/**
	 * Display Secondary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_facebook_secondary_navigation() {
	    if ( has_nav_menu( 'secondary' ) ) {
		    ?>
		    <nav class="secondary-navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'storefront' ); ?>">
			    <?php
				    wp_nav_menu(
					    array(
						    'theme_location'	=> 'secondary',
						    'fallback_cb'		=> '',
					    )
				    );
			    ?>
		    </nav><!-- #site-navigation -->
		    <?php
		}
	}
}


if ( ! function_exists( 'storefront_product_category_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_product_category_navigation() {
		?>

		<nav id="site-navigation" class="main-navigation category-navigation" role="navigation" aria-label="Primary Navigation">
			<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span>&nbsp;</span></button>
			<div class="primary-navigation">
				<ul id="menu-primary-menu" class="menu">
			<?php

			  $taxonomy     = 'product_cat';
			  $orderby      = 'name';  
			  $show_count   = 0;      // 1 for yes, 0 for no
			  $pad_counts   = 0;      // 1 for yes, 0 for no
			  $hierarchical = 1;      // 1 for yes, 0 for no  
			  $title        = '';  
			  $empty        = 0;

			  $args = array(
			         'taxonomy'     => $taxonomy,
			         'orderby'      => $orderby,
			         'show_count'   => $show_count,
			         'pad_counts'   => $pad_counts,
			         'hierarchical' => $hierarchical,
			         'title_li'     => $title,
			         'hide_empty'   => $empty
			  );
			 $all_categories = get_categories( $args );
			
			 foreach ($all_categories as $cat) {
			    if($cat->category_parent == 0) {
			        $category_id = $cat->term_id;   
			        $args2 = array(
			                'taxonomy'     => $taxonomy,
			                'child_of'     => 0,
			                'parent'       => $category_id,
			                'orderby'      => $orderby,
			                'show_count'   => $show_count,
			                'pad_counts'   => $pad_counts,
			                'hierarchical' => $hierarchical,
			                'title_li'     => $title,
			                'hide_empty'   => $empty
			        );
			        $sub_cats = get_categories( $args2 );

			        echo '<li id="menu-item-'.$category_id.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-4 current_page_item';
			        if($sub_cats):
			        	echo ' menu-item-has-children';
			        endif;
			        echo ' menu-item-'.$category_id.'">';
			        echo '<a href="'.get_term_link($cat->slug, 'product_cat').'">'.$cat->name.'</a>';
			        
			        if($sub_cats) {
			        	echo '<ul class="sub-menu">';
			            foreach($sub_cats as $sub_category) {
			            	echo '<li id="menu-item-'.$sub_category->term_id.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.$sub_category->term_id.'">';
			            	echo '<a href="'.get_term_link($sub_category->slug, 'product_cat').'">'.$sub_category->name.'</a>';
			                echo '</li>';
			            }  
			            echo '</ul>';
			        }

			        echo '</li>';
			    }       
			}
			?>
				</ul>
			</div>
		</nav>
		<?php
		/*
		<div class="storefront-primary-navigation"> <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="Primary Navigation">
<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span>Menu</span></button>
<div class="primary-navigation">
	<ul id="menu-primary-menu" class="menu">
		<li id="menu-item-56" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-4 current_page_item menu-item-has-children menu-item-56">
			<a href="https://sandbox.qoobit.com/wordpress/">Shop</a>
			<ul class="sub-menu">
				<li id="menu-item-80" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-80">
					<a href="https://sandbox.qoobit.com/wordpress/shop/zzz/">zzz</a>
				</li>
			</ul>
		</li>
	</ul>
</div><div class="handheld-navigation"><ul id="menu-user-menu-1" class="menu"><li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-57"><a href="https://sandbox.qoobit.com/wordpress/my-account/">My Account</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-58"><a href="https://sandbox.qoobit.com/wordpress/checkout/">Checkout</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-59"><a href="https://sandbox.qoobit.com/wordpress/cart/">Cart</a></li>
</ul></div> </nav> 

*/
		?>
		<!-- #site-navigation -->

		<?php
	}
}

