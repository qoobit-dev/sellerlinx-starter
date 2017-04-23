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



function wpsd_add_product_args() {
    /*
    global $wp_post_types;
 
    $wp_post_types['products']->show_in_rest = true;
    $wp_post_types['products']->rest_base = 'products';
    $wp_post_types['products']->rest_controller_class = 'WP_REST_Posts_Controller';
*/
}
add_action( 'init', 'wpsd_add_product_args', 30 );


/**
 * Dequeue the Storefront Parent theme core CSS
 */
$videoIndex = 0;
$bannerIndex = 0;
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
    register_widget('sellerlinx_social_link');
	
    register_sidebar(
        array (
            'name'          => __('Main Banner', 'sellerlinx'),
            'id'            => 'sidebar-banner',
            'before_widget' => '',
            'after_widget'  => ''
        )
    );

    register_sidebar(
        array (
            'name'          => __('Social Links', 'sellerlinx'),
            'id'            => 'sidebar-social-links',
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
        global $videoIndex;
        global $bannerIndex;
        ?>
        <div id="banner-<?php echo $bannerIndex;?>" class="banner" style="<?php if( !empty($instance['background_color']) ): ?>background-color:<?php echo $instance['background_color']; ?>;<?php endif;?><?php if( !empty($instance['image_url']) ): ?> background-image:url(<?php echo esc_url($instance['image_url']);?>);<?php endif;?>width:100%; height:100%;<?php 
            if($instance['anchor_position']>=3&&$instance['anchor_position']<=5){
                echo 'display:table;';
            }

            ?>">
        	<?php if( !empty($instance['video_url']) ): 

				$provider = parseProvider($instance['video_url']);
				$code = parseCode($instance['video_url']);

				if($provider=="youtube"):

				?>
<div class="banner-video" id="video-<?php echo $videoIndex?>"></div>
<script>
videoPlayers[videoCount] = new Object();
codes[videoCount] = '<?php echo $code;?>';
bannerSize[videoCount] = '<?php echo $instance['size']?>';
videoCount++;
</script>
				<?php endif;?>

        	<?php 
                $videoIndex++;
            endif;?>
        	<div class="overlay" style="<?php if( !is_null($instance['overlay_opacity']) ): echo 'opacity:'.$instance['overlay_opacity'].';'; endif;?><?php if( !empty($instance['overlay_color']) ): echo 'background:'.$instance['overlay_color'].';'; endif;?>"></div>
        	<div class="container" style="<?php 
            if($instance['anchor_position']>=3&&$instance['anchor_position']<=5){
                echo 'display: table-cell;vertical-align: middle;position:relative;';
            }
            if($instance['anchor_position']<3){
                echo 'top:0px;';
            }
            else if($instance['anchor_position']>=6){
                echo 'bottom:0px;';
            }
            ?>">
        		<div class="caption" style="text-align:<?php 
                    if($instance['anchor_position']%3==2){
                        echo 'right';
                    }
                    else if($instance['anchor_position']%3==1){
                        echo 'center';
                    }
                    else{
                        echo 'left';
                    }
                ?>;">
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
            $bannerIndex++;
        echo $after_widget;

    }

	
	
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['content'] = stripslashes(wp_filter_post_kses($new_instance['content']));
        
        $instance['image_url'] = strip_tags($new_instance['image_url']);
        $instance['video_url'] = stripslashes($new_instance['video_url']);
        $instance['link_url'] = stripslashes($new_instance['link_url']);

        $instance['background_color'] = stripslashes($new_instance['background_color']);
        $instance['overlay_color'] = stripslashes($new_instance['overlay_color']);
        $instance['overlay_opacity'] = stripslashes($new_instance['overlay_opacity']);
        $instance['anchor_position'] = stripslashes($new_instance['anchor_position']);
        $instance['size'] = stripslashes($new_instance['size']);


        return $instance;

    }

    function form($instance) {
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sellerlinx'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Content', 'sellerlinx'); ?></label><br/>
            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name('content'); ?>" id="<?php echo $this->get_field_id('content'); ?>"><?php if( !empty($instance['content']) ): echo htmlspecialchars_decode($instance['content']); endif; ?></textarea>
        </p>
        
		<p>
            <label for="<?php echo $this->get_field_id('video_url'); ?>"><?php _e('Background Video', 'sellerlinx'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('video_url'); ?>" id="<?php echo $this->get_field_id('video_url'); ?>" value="<?php if( !empty($instance['video_url']) ): echo $instance['video_url']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Background Image', 'sellerlinx'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('image_url'); ?>" id="<?php echo $this->get_field_id('image_url'); ?>" value="<?php if( !empty($instance['image_url']) ): echo $instance['image_url']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_url'); ?>"><?php _e('Button Link', 'sellerlinx'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('link_url'); ?>" id="<?php echo $this->get_field_id('link_url'); ?>" value="<?php if( !empty($instance['link_url']) ): echo $instance['link_url']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('background_color'); ?>"><?php _e('Background Color', 'sellerlinx'); ?></label><br/>
            <input type="color" name="<?php echo $this->get_field_name('background_color'); ?>" id="<?php echo $this->get_field_id('background_color'); ?>" value="<?php if( !empty($instance['background_color']) ): echo $instance['background_color']; endif; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('overlay_color'); ?>"><?php _e('Overlay Color', 'sellerlinx'); ?></label><br/>
            <input type="color" name="<?php echo $this->get_field_name('overlay_color'); ?>" id="<?php echo $this->get_field_id('overlay_color'); ?>" value="<?php if( !empty($instance['overlay_color']) ): echo $instance['overlay_color']; endif; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('overlay_opacity'); ?>"><?php _e('Overlay Opacity', 'sellerlinx'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('overlay_opacity'); ?>" id="<?php echo $this->get_field_id('overlay_opacity'); ?>" value="<?php if( !is_null($instance['overlay_opacity']) ): echo $instance['overlay_opacity']; endif; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('anchor_position'); ?>"><?php _e('Text Anchor Position', 'sellerlinx'); ?></label><br/>
            <select name="<?php echo $this->get_field_name('anchor_position'); ?>" id="<?php echo $this->get_field_id('anchor_position'); ?>">
                <option value="0"<?php if($instance['anchor_position']==0) echo ' selected'; ?>>Top Left</option>
                <option value="1"<?php if($instance['anchor_position']==1) echo ' selected'; ?>>Top Center</option>
                <option value="2"<?php if($instance['anchor_position']==2) echo ' selected'; ?>>Top Right</option>
                <option value="3"<?php if($instance['anchor_position']==3) echo ' selected'; ?>>Middle Left</option>
                <option value="4"<?php if($instance['anchor_position']==4) echo ' selected'; ?>>Middle Center</option>
                <option value="5"<?php if($instance['anchor_position']==5) echo ' selected'; ?>>Middle Right</option>
                <option value="6"<?php if($instance['anchor_position']==6) echo ' selected'; ?>>Bottom Left</option>
                <option value="7"<?php if($instance['anchor_position']==7) echo ' selected'; ?>>Bottom Center</option>
                <option value="8"<?php if($instance['anchor_position']==8) echo ' selected'; ?>>Bottom Right</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size', 'sellerlinx'); ?></label><br/>
            <select name="<?php echo $this->get_field_name('size'); ?>" id="<?php echo $this->get_field_id('size'); ?>">
                <option value="auto"<?php if($instance['size']=='auto') echo ' selected'; ?>>Default</option>
                <option value="100% auto"<?php if($instance['size']=='100% auto') echo ' selected'; ?>>Full Width</option>
                <option value="auto 100%"<?php if($instance['size']=='auto 100%') echo ' selected'; ?>>Full Height</option>
                <option value="cover"<?php if($instance['size']=='cover') echo ' selected'; ?>>Cover</option>
            </select>
        </p>
    <?php

    }

}








class sellerlinx_social_link extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'sellerlinx-social-link-widget',
            __( 'Sellerlinx - Social Link', 'sellerlinx' )
        );
    }

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;
        
        $platform = '';

        $url = $instance['url'];
        if(strpos($url, 'youtube') !== false){ $platform = 'youtube'; }
        if(strpos($url, 'facebook') !== false){ $platform = 'facebook'; }
        if(strpos($url, 'twitter') !== false){ $platform = 'twitter'; }
        if(strpos($url, 'instagram') !== false){ $platform = 'instagram'; }
        if(strpos($url, 'weibo') !== false){ $platform = 'weibo'; }
        if(strpos($url, 'vimeo') !== false){ $platform = 'vimeo'; }
        ?>
        <a href="<?php echo $instance['url'];?>" class="social-icon<?php if(!empty($platform)): echo ' '.$platform.'-icon'; endif;?>" style="color:<?php echo $instance['text_color'];?>;" target="_blank"><?php echo $instance['title']; ?></a>
        <?php
        echo $after_widget;

    }

    
    
    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = stripslashes($new_instance['url']);
        $instance['icon_color'] = stripslashes($new_instance['icon_color']);
        $instance['text_color'] = stripslashes($new_instance['text_color']);

        return $instance;

    }

    function form($instance) {
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sellerlinx'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty($instance['title']) ): echo $instance['title']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link URL', 'sellerlinx'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('url'); ?>" id="<?php echo $this->get_field_id('url'); ?>" value="<?php if( !empty($instance['url']) ): echo $instance['url']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_color'); ?>"><?php _e('Icon Color', 'sellerlinx'); ?></label><br/>
            <input type="color" name="<?php echo $this->get_field_name('icon_color'); ?>" id="<?php echo $this->get_field_id('icon_color'); ?>" value="<?php if( !empty($instance['icon_color']) ): echo $instance['icon_color']; endif; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text_color'); ?>"><?php _e('Text Color', 'sellerlinx'); ?></label><br/>
            <input type="color" name="<?php echo $this->get_field_name('text_color'); ?>" id="<?php echo $this->get_field_id('text_color'); ?>" value="<?php if( !empty($instance['text_color']) ): echo $instance['text_color']; endif; ?>">
        </p>
        
    <?php

    }

}