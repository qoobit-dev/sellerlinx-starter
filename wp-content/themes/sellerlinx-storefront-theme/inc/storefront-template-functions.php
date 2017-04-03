<?php
/**
 * Storefront template functions.
 *
 * @package storefront
 */



if ( ! function_exists( 'sellerlinx_languages' ) ) {
	function sellerlinx_languages() {
		?>
<script>
jQuery(document).ready(function(){
    jQuery(".qtranxs-lang-menu a").first().hide();
});
</script>
         <?php
    }
}


if ( ! function_exists( 'sellerlinx_seo' ) ) {
	/**
	 * Home Page Banners
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_seo() {
		?>
<meta name="description" content="" />
<meta property="og:title" content=""/>
<meta property="og:site_name" content="" />
<meta property="og:type" content="article"/>
<meta property="og:image" content=""/>
<meta property="og:url" content=""/>
<meta property="og:description" content=""/>
<?php
	}

}




if ( ! function_exists( 'sellerlinx_home_videos' ) ) {
	/**
	 * Home Page Banners
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_home_videos() {
		$height = get_theme_mod( 'sellerlinx_banner_height' ); 
		$banner_size = get_theme_mod( 'sellerlinx_banner_size' ); 
		?>
<script>

var videoPlayers = new Array();
var codes = new Array();
var videoCount = 0;


function videoResize(){
    var bannerWidth = jQuery(".banners").width();
    var bannerHeight = jQuery(".banners").height();
    var windowWidth = jQuery(window).width();

    var windowAspect = windowWidth/bannerHeight;
    var bannerAspect = bannerWidth/bannerHeight;
    var videoAspect = 16/9;
    var w = 0;
    var h = 0;
    var t = 0;
    var l = 0;
    


    <?php if($banner_size=="100% auto"): ?>
	w = windowWidth;
    h = w/videoAspect;
    l = 0;

    //vertical align to top
    t = (bannerHeight - h) / 2;

    jQuery(".banner-video").css("top",t+"px");
    jQuery(".banner-video").css("left",l+"px");
    jQuery(".banner-video").css("height",h+"px");
    jQuery(".banner-video").css("width",w+"px");
    jQuery(".banner-video").css("min-width",w+"px");
	<?php elseif($banner_size=="auto 100%"): ?>
	h = bannerHeight;
	w = h*videoAspect;
	t = 0;
	
    //vertical align to top
    l = (bannerWidth - w) / 2;

    jQuery(".banner-video").css("top",t+"px");
    jQuery(".banner-video").css("left",l+"px");
    jQuery(".banner-video").css("height",h+"px");
    jQuery(".banner-video").css("width",w+"px");
    jQuery(".banner-video").css("min-width",w+"px");
	<?php elseif($banner_size=="auto"): ?>
	t = 0;
	l = 0;
	jQuery(".banner-video").css("top",t+"px");
    jQuery(".banner-video").css("left",l+"px");
    jQuery(".banner-video").css("height","100%");
    jQuery(".banner-video").css("width","100%");
    jQuery(".banner-video").css("min-width","100%");
	<?php elseif($banner_size=="cover"): ?>
	w = bannerWidth;
	h = bannerWidth/videoAspect;
	//need to crop top bottom
    if(Math.ceil(h)>jQuery(".banners").height()){
    	l = 0;
    	t = -(h-jQuery(".banners").height())/2;
    }
    //need to crop left right
    else{
    	h = bannerHeight;
    	w = h*videoAspect;

    	t = 0;
    	l = -(w-jQuery(".banners").width())/2;
    }

    jQuery(".banner-video").css("top",t+"px");
    jQuery(".banner-video").css("left",l+"px");
    jQuery(".banner-video").css("height",h+"px");
    jQuery(".banner-video").css("width",w+"px");
    jQuery(".banner-video").css("min-width",w+"px");
    <?php endif;?>

	

}
jQuery(document).ready(function(){videoResize();});
jQuery(window).resize(function(){videoResize();});

var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

function onYouTubeIframeAPIReady() {
   
    for(var i = 0;i<videoPlayers.length;i++){
        videoPlayers[i]= new YT.Player('video-'+i, {
              width: 'auto',
              height: 'auto',
              videoId: codes[i],
              playerVars: { 'autoplay': 1,'rel':0,'showinfo':0,'controls':0 },
              events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
              }
            });

        videoPlayers[i].index = i;
    }
	
	videoResize();
}

function onPlayerReady(event) {
    event.target.mute();
    event.target.setPlaybackQuality("hd1080");
}

function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.PLAYING) {
		console.log("PLAYING");
	}
	if (event.data == YT.PlayerState.PAUSED) {
		console.log("PAUSED");
	}
	if (event.data == YT.PlayerState.ENDED) {
		console.log("ENDED");
		var index = event.target.index;
		videoPlayers[index].loadVideoById(codes[index]);
		videoPlayers[index].setPlaybackQuality("hd1080");
	}
	
}
</script>
		<?php
	}
}


if ( ! function_exists( 'sellerlinx_home_banners' ) ) {
	/**
	 * Home Page Banners
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_home_banners() {

		if(is_front_page()): ?>
<div class="banners<?php if(get_theme_mod( 'sellerlinx_banner_configuration' )=='contained'):?> contained<?php endif;?>">
	<?php
	if ( is_active_sidebar( 'sidebar-banner' ) ) :
		dynamic_sidebar( 'sidebar-banner' );
	endif;
	?>

</div>
<script>
var intervalTime = <?php echo get_theme_mod( 'sellerlinx_banner_transition_duration' );?>;
var interval;
var bannerCount;
var currentBanner = 0;
var nextBannerIndex;

jQuery(document).ready(function(){
	
	bannerCount = jQuery(".banners").children().length;

	if(bannerCount>1){
		interval = setInterval(function(){nextBanner();},intervalTime);		
	}
	

	if(bannerCount==0) {
		jQuery(".banners").hide();
	}
	else{
		//jQuery(".banner").hide();
		//jQuery(".banners").children().first().show();	
	}
	});

<?php $transition = get_theme_mod( 'sellerlinx_banner_transition'); ?>
function nextBanner(){
	clearInterval(interval);
	interval = setInterval(function(){nextBanner();},intervalTime);
	nextBannerIndex = currentBanner+1;

	if(nextBannerIndex==bannerCount) nextBannerIndex=0;



	<?php if($transition=="fade"):
	?>
	jQuery(".banners").children().eq(currentBanner).css("zIndex","2");
	jQuery(".banners").children().eq(nextBannerIndex).css("zIndex","3");
	jQuery(".banners").children().eq(nextBannerIndex).css("opacity","0");
	//jQuery(".banners").children().eq(nextBannerIndex).show();
	jQuery(".banners").children().eq(nextBannerIndex).fadeTo( 500 , 1, function() {
	    jQuery(".banners").children().eq(currentBanner).css("zIndex","1");
	    //jQuery(".banners").children().eq(currentBanner).hide();
	    currentBanner = nextBannerIndex;
	  });
	<?php elseif($transition=="slide-horizontal"):?>
	jQuery(".banners").children().eq(currentBanner).css("zIndex","2");
	jQuery(".banners").children().eq(currentBanner).css("left","0px");
	jQuery(".banners").children().eq(nextBannerIndex).css("zIndex","3");
	jQuery(".banners").children().eq(nextBannerIndex).css("left",jQuery(".banner").width());
	//jQuery(".banners").children().eq(nextBannerIndex).show();
	jQuery(".banners").children().eq(nextBannerIndex).animate({
		left:"0"
	}, 500 , function() {
	    jQuery(".banners").children().eq(currentBanner).css("zIndex","1");
	    //jQuery(".banners").children().eq(currentBanner).hide();
	    currentBanner = nextBannerIndex;
	  });
	<?php elseif($transition=="slide-vertical"):?>
	jQuery(".banners").children().eq(currentBanner).css("zIndex","2");
	jQuery(".banners").children().eq(currentBanner).css("top","0px");
	jQuery(".banners").children().eq(nextBannerIndex).css("zIndex","3");
	jQuery(".banners").children().eq(nextBannerIndex).css("top",-jQuery(".banner").height());
	//jQuery(".banners").children().eq(nextBannerIndex).show();
	jQuery(".banners").children().eq(nextBannerIndex).animate({
		top:"0"
	}, 500 , function() {
	    jQuery(".banners").children().eq(currentBanner).css("zIndex","1");
	    //jQuery(".banners").children().eq(currentBanner).hide();
	    currentBanner = nextBannerIndex;
	  });
	<?php endif;?>


	
}

function prevBanner(){
	clearInterval(interval);
	interval = setInterval(function(){nextBanner();},intervalTime);

	var prevBanner = currentBanner-1;
	if(currentBanner<0) prevBanner=bannerCount-1;
	<?php /*
		if($transition=="fade"):
		elseif($transition=="slide-horizontal":
		elseif($transition=="slide-vertical":
		endif;
		*/?>
	
}
</script>
		<?php endif;
	}
}

if ( ! function_exists( 'sellerlinx_banner_css' ) ) {
	/**
	 * Banner Extra CSS
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_banner_css() {
		$height = get_theme_mod( 'sellerlinx_banner_height' ); 
		$banner_size = get_theme_mod( 'sellerlinx_banner_size' ); 
		?>
<!-- SELLERLINX BANNER CSS !-->
<style>
.banners{
	height:<?php echo $height;?>px;
}
.banner{
	height:<?php echo $height;?>px;
	background-size: <?php echo $banner_size;?>;
}
</style>
<?php 
	}
}



if ( ! function_exists( 'sellerlinx_product_css' ) ) {
	/**
	 * Banner Extra CSS
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_product_css() {
		$border = get_theme_mod( 'sellerlinx_product_border_size' ); 
		$color = get_theme_mod( 'sellerlinx_product_border_color' ); 
		?>
<!-- SELLERLINX PRODUCT CSS !-->
<style>
.product-thumbnail img,.product-image img{
	border: solid <?php echo $border;?>px <?php echo $color;?>;
	border-radius: 0px;
	
}
</style>
<?php 
	}
}


if ( ! function_exists( 'sellerlinx_google_analytics' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_google_analytics() {
		echo get_theme_mod( 'sellerlinx_google_analytics' );
	}
}


if ( ! function_exists( 'sellerlinx_background' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_background() {
		?>
		<style>
		#content .col-full{
			background-color:#<?php echo get_theme_mod( 'sellerlinx_content_background_color' );?>;
		}
		body.custom-background{
			background-image:url(<?php echo get_theme_mod( 'sellerlinx_background_url' );?>);
			background-repeat:<?php echo get_theme_mod( 'sellerlinx_background_repeat' );?>;
			background-size: <?php echo get_theme_mod( 'sellerlinx_background_size' );?>;
			background-attachment:<?php echo get_theme_mod( 'sellerlinx_background_attachment' );?>;;
			background-position: center top;
		}
		</style>
		<?php
		if(!empty(get_theme_mod( 'sellerlinx_background_url' ))):?>
		<script>
		jQuery(document).ready(function(){
			jQuery("body").addClass('custom-background');
		});
		</script>
<?php
		endif;
	}
}

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


if ( ! function_exists( 'storefront_facebook_product_search' ) ) {
	/**
	 * Display Product Search
	 *
	 * @since  1.0.0
	 * @uses  storefront_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function storefront_facebook_product_search() {
		if ( storefront_is_woocommerce_activated() ) { ?>
			<div class="site-search">
				<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
			</div>
		<?php
		}
	}
}

if ( ! function_exists( 'storefront_facebook_header_cart' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses  storefront_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function storefront_facebook_header_cart() {
		if ( storefront_is_woocommerce_activated() ) {
			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
		?>
		<ul id="site-header-cart" class="site-header-cart menu">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php storefront_cart_link(); ?>
			</li>
			<li>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</li>
		</ul>
		<?php
		}
	}
}


if ( ! function_exists( 'storefront_handheld_footer_social_links' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses  storefront_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function storefront_handheld_footer_social_links() {
		if ( is_active_sidebar( 'sidebar-social-links' ) ) :
			dynamic_sidebar( 'sidebar-social-links' );
		endif;		
	}
}

if ( ! function_exists( 'storefront_handheld_footer_sitemap' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses  storefront_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function storefront_handheld_footer_sitemap() {
		$args = array('menu'=>'Sitemap');
		wp_nav_menu($args);
	}
}

if ( ! function_exists( 'storefront_handheld_footer_bar_contact_link' ) ) {
	/**
	 * The search callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function storefront_handheld_footer_bar_contact_link() {
		echo '<a href="'.esc_url( home_url( '/' ) ).'contact">' . esc_attr__( 'Contact', 'storefront' ) . '</a>';
	}
}

if ( ! function_exists( 'storefront_handheld_footer_bar' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses  storefront_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function storefront_handheld_footer_bar() {
		$links = array(
			'my-account' => array(
				'priority' => 10,
				'callback' => 'storefront_handheld_footer_bar_account_link',
			),
			'search'     => array(
				'priority' => 20,
				'callback' => 'storefront_handheld_footer_bar_search',
			),
			'cart'       => array(
				'priority' => 30,
				'callback' => 'storefront_handheld_footer_bar_cart_link',
			),
			'contact'       => array(
				'priority' => 40,
				'callback' => 'storefront_handheld_footer_bar_contact_link',
			),
		);

		if ( wc_get_page_id( 'myaccount' ) === -1 ) {
			unset( $links['my-account'] );
		}

		if ( wc_get_page_id( 'cart' ) === -1 ) {
			unset( $links['cart'] );
		}
/*
		if ( wc_get_page_id( 'contact' ) === -1 ) {

			unset( $links['contact'] );
		}
		*/

		$links = apply_filters( 'storefront_handheld_footer_bar_links', $links );
		?>
		<div class="storefront-handheld-footer-bar">
			<ul class="columns-<?php echo count( $links ); ?>">
				<?php foreach ( $links as $key => $link ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>">
						<?php
						if ( $link['callback'] ) {
							call_user_func( $link['callback'], $key, $link );
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		
	}
}

if ( ! function_exists( 'sellerlinx_site_icon' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_site_icon() {
		$icon_url = get_theme_mod( 'sellerlinx_icon_url' );	
if(!empty($icon_url)):
?>
<link rel="icon" href="<?php echo $icon_url;?>">
<link rel="icon" href="<?php echo $icon_url;?>" sizes="32x32" />
<link rel="icon" href="<?php echo $icon_url;?>" sizes="192x192" />
<link rel="apple-touch-icon-precomposed" href="<?php echo $icon_url;?>" />
<meta name="msapplication-TileImage" content="<?php echo $icon_url;?>" />
		<?php
		endif;
	}
}

if ( ! function_exists( 'sellerlinx_site_branding' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function sellerlinx_site_branding() {
		?>
		<div class="site-branding">
			<?php sellerlinx_site_title_or_logo(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'sellerlinx_site_title_or_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @since 2.1.0
	 * @param bool $echo Echo the string or return it.
	 * @return string
	 */
	function sellerlinx_site_title_or_logo( $echo = true ) {
		$logo_url = get_theme_mod( 'sellerlinx_logo_url' );

		if(empty($logo_url)){
			$tag = is_home() ? 'h1' : 'div';

			$html = '<' . esc_attr( $tag ) . ' class="beta site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></' . esc_attr( $tag ) .'>';

			if ( '' !== get_bloginfo( 'description' ) ) {
				$html .= '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
			}
		}
		else{
			$html    = sprintf( '<a href="%1$s" class="site-logo-link" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				'<img src="'.$logo_url.'"/>'
			);

			
		}

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}
}


if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
	function woocommerce_template_loop_product_thumbnail() {
	    global $post;

	    $thumbnail_url = get_post_meta(get_the_ID(),"thumbnail_url",true);
	    if(!empty($thumbnail_url)):?>
	    <div class="product-thumbnail">
	    	<img src="<?php echo $thumbnail_url;?>"/>
		</div>
	<?php
	    endif;
	    /*
	    if ( has_post_thumbnail() )
	          echo get_the_post_thumbnail( $post->ID, 'shop_catalog' );
	          */
	}
}

if ( ! function_exists( 'sellerlinx_show_product_images' ) ) {
	function sellerlinx_show_product_images() {
	    
		global $post, $product;?>
		<div class="images">	
		<?php
		$image_url = get_post_meta(get_the_ID(),"image_url",true);
	    if(!empty($image_url)):?>
	    <div class="product-image">
	    	<img src="<?php echo $image_url;?>"/>
		</div>
		<?php endif;?>
		</div><?php
	}
}
