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
	remove_action( 'storefront_header', 'storefront_site_branding', 20 );
	remove_action( 'wp_head', 'wp_site_icon', 99 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
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




/**
 * Banners
 */

function parseProvider($u){
    
    $url = parse_url($u);
    
    $provider = ''; 
    switch($url['host']){
        case "youtu.be":
        case "www.youtube.com":
        case "youtube.com":
            $provider="youtube";
            break;
        case "www.vimeo.com":
        case "vimeo.com":
            $provider="vimeo";
            break;
        case "www.soundcloud.com":
        case "soundcloud.com":
            $provider="soundcloud";
            break;
        default:
            break;
    }
    
    return $provider;
}

function parseCode($u,$full=false){
    
    $url = parse_url($u);
    $code ='';
    switch($url['host']){
        case "youtu.be":
            $code = $url['path'];
            $code = substr($code,1,strlen($code)-1);
            break;
        case "www.youtube.com":
        case "youtube.com":
            parse_str($url['query']);
            $code=$v; //7KdMiRUbHi0 
            break;
        case "www.vimeo.com":
        case "vimeo.com":
            preg_match('/^http:\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+).*$/', $u, $match);
            $code=$match[3];
            break;
        case "www.soundcloud.com":
        case "soundcloud.com":

            $code = $url['path'];
            $parts = explode("/",$code);

            $user =$parts[1];
            $track =$parts[2];
            
            $code = $track;
            if($full) $code=$user.'/'.$code;

            break;
        default:
            break;
    }   
    
    return $code;
}


add_action('widgets_init', 'sellerlinx_register_widgets');
function sellerlinx_register_widgets() {    
	register_widget('sellerlinx_banner');
	
    register_sidebar(
        array (
            'name'          => __('Main Banner', 'sellerlinx'),
            'id'            => 'sidebar-banner',
            'before_widget' => '',
            'after_widget'  => ''
        )
    );
}



class sellerlinx_banner extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'sellerlinx-banner-widget',
			__( 'Sellerlinx - Main Banner', 'sellerlinx' )
		);
	}

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;

        ?>
        <div class="banner"<?php if( !empty($instance['image_url']) ): ?> style="background-image:url(<?php echo esc_url($instance['image_url']); ?>);width:100%; height:100%;"<?php endif;?>>
        	<?php if( !empty($instance['video_url']) ): 

				$provider = parseProvider($instance['video_url']);
				$code = parseCode($instance['video_url']);

				if($provider=="youtube"):

				?>
<div class="banner-video" id="video-0"></div>
<script>
var player;
var code = '<?php echo $code;?>';
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

function onYouTubeIframeAPIReady() {
	player = new YT.Player('video-0', {
	  width: '100%',
	  videoId: code,
	  playerVars: { 'autoplay': 1,'rel':0,'showinfo':0,'controls':0 },
	  events: {
		'onReady': onPlayerReady,
		'onStateChange': onPlayerStateChange
	  }
	});
	
	videoResize();
}

function videoResize(){
    
    var h = $(window).height();
    var w = h*16/9;

	//need to crop top bottom
    if(Math.ceil(w)<$(window).width()){
        w = $(window).width();
        h = w*9/16;
        $("#video-0").css("left","0px");
        $("#video-0").css("top",-(h-$(window).height())/2+"px");
    }
    //need to crop left right
    else{
        $("#video-0").css("top","0px");
        $("#video-0").css("left",-(w-$(window).width())/2+"px");
    }

    $("#video-0").css("height",h+"px");
    $("#video-0").css("width",w+"px");    
}
$(document).ready(function(){videoResize();});
$(window).resize(function(){videoResize();});
function onPlayerReady(event) {}

function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.PLAYING) {
		console.log("PLAYING");
	}
	if (event.data == YT.PlayerState.PAUSED) {
		console.log("PAUSED");
	}
	if (event.data == YT.PlayerState.ENDED) {
		console.log("ENDED");
		player.loadVideoById(code);
	}
	
}
</script>
				<?php endif;?>
        	<?php endif;?>
        	<div class="overlay"></div>
        	<div class="container">
        		<div class="caption">
		        	<div class="col-full">
		            <h2><?php if( !empty($instance['title']) ): echo apply_filters('widget_title', $instance['title']); endif; ?></h2>
					<?php if( !empty($instance['content']) ):?>
						<div>
							<?php echo htmlspecialchars_decode(apply_filters('widget_title', $instance['content']));?>
						</div>
					<?php endif; ?>	
					<?php if( !empty($instance['link_url']) ):?>
					<a class="button" href="<?php echo $instance['link_url'];?>">Learn More</a>
					<?php endif;?>
					</div>
				</div>
		    </div>
	    </div>
        <?php

        echo $after_widget;

    }

	
	
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['content'] = stripslashes(wp_filter_post_kses($new_instance['content']));
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['image_url'] = strip_tags($new_instance['image_url']);
        $instance['video_url'] = stripslashes($new_instance['video_url']);
        $instance['link_url'] = stripslashes($new_instance['link_url']);

        return $instance;

    }

    function form($instance) {
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'qoobit'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Content', 'qoobit'); ?></label><br/>
            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name('content'); ?>" id="<?php echo $this->get_field_id('content'); ?>"><?php if( !empty($instance['content']) ): echo htmlspecialchars_decode($instance['content']); endif; ?></textarea>
        </p>
        
		<p>
            <label for="<?php echo $this->get_field_id('video_url'); ?>"><?php _e('Background Video', 'qoobit'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('video_url'); ?>" id="<?php echo $this->get_field_id('video_url'); ?>" value="<?php if( !empty($instance['video_url']) ): echo $instance['video_url']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Background Image', 'qoobit'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" value="<?php if( !empty($instance['image_url']) ): echo $instance['image_url']; endif; ?>" class="widefat">
        </p>
         <p>
            <label for="<?php echo $this->get_field_id('link_url'); ?>"><?php _e('Button Link', 'qoobit'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('link_url'); ?>" id="<?php echo $this->get_field_id('link_url'); ?>" value="<?php if( !empty($instance['link_url']) ): echo $instance['link_url']; endif; ?>" class="widefat">
        </p>
        
    <?php

    }

}



