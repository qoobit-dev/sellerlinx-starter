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
        ?>
        <div class="banner" style="<?php if( !empty($instance['background_color']) ): ?>background-color:<?php echo $instance['background_color']; ?>;<?php endif;?><?php if( !empty($instance['image_url']) ): ?> background-image:url(<?php echo esc_url($instance['image_url']);?>);<?php endif;?>width:100%; height:100%;<?php 
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
videoCount++;
</script>
				<?php endif;?>
        	<?php endif;?>
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
$videoIndex++;
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
            <input type="text" name="<?php echo $this->get_field_name('overlay_opacity'); ?>" id="<?php echo $this->get_field_id('overlay_opacity'); ?>" value="<?php if( !empty($instance['overlay_opacity']) ): echo $instance['overlay_opacity']; endif; ?>">
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









function sellerlinx_theme_get_settings(  $data ) {
    
    $data = array();
    $data['icon_url'] = get_theme_mod( 'sellerlinx_icon_url' ); 
    $data['logo_url'] = get_theme_mod( 'sellerlinx_logo_url' ); 
    $data['product_border_size'] = get_theme_mod( 'sellerlinx_product_border_size' ); 
    $data['product_border_color'] = get_theme_mod( 'sellerlinx_product_border_color' ); 
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
    //$source_data['r']=$json_input;

    $source_data = array();
    $source_data['icon_url'] = get_theme_mod( 'sellerlinx_icon_url' );
    if(!empty($json_input->icon_url)) $source_data['icon_url']=$json_input->icon_url;
    $source_data['logo_url'] = get_theme_mod( 'sellerlinx_logo_url' );
    if(!empty($json_input->logo_url)) $source_data['logo_url']=$json_input->logo_url; 
    $source_data['product_border_size'] = get_theme_mod( 'sellerlinx_product_border_size' ); 
    if(!empty($json_input->product_border_size)) $source_data['product_border_size']=$json_input->product_border_size; 
    $source_data['product_border_color'] = get_theme_mod( 'sellerlinx_product_border_color' ); 
    if(!empty($json_input->product_border_color)) $source_data['product_border_color']=$json_input->product_border_color; 
    $source_data['facebook_products_per_page'] = get_theme_mod( 'storefront_layout_facebook_products_per_page' ); 
    if(!empty($json_input->facebook_products_per_page)) $source_data['facebook_products_per_page']=$json_input->facebook_products_per_page; 
    $source_data['products_per_page'] = get_theme_mod( 'storefront_layout_products_per_page' ); 
    if(!empty($json_input->products_per_page)) $source_data['products_per_page']=$json_input->products_per_page; 
    $source_data['facebook_columns'] = get_theme_mod( 'storefront_layout_facebook_columns' ); 
    if(!empty($json_input->facebook_columns)) $source_data['facebook_columns']=$json_input->facebook_columns; 
    $source_data['columns'] = get_theme_mod( 'storefront_layout_columns' ); 
    if(!empty($json_input->columns)) $source_data['columns']=$json_input->columns; 

    $source_data['background_color'] = get_theme_mod( 'background_color' ); 
    if(!empty($json_input->background_color)) $source_data['background_color']=$json_input->background_color; 
    $source_data['background_url'] = get_theme_mod( 'sellerlinx_background_url' ); 
    if(!empty($json_input->background_url)) $source_data['background_url']=$json_input->background_url; 
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
    if(!empty($json_input->custom_css)) $source_data['custom_css']=$json_input->custom_css; 
    
    //update theme data
    set_theme_mod('sellerlinx_icon_url',$source_data['icon_url']);
    set_theme_mod('sellerlinx_logo_url',$source_data['logo_url']);
    set_theme_mod('sellerlinx_product_border_size',$source_data['product_border_size']);
    set_theme_mod('sellerlinx_product_border_color',$source_data['product_border_color']);
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



function sellerlinx_get_settings(  $data ) {
    
    $data = array();
    $data['blog_public'] = intval(get_option("blog_public"));

    $mm_options = get_option("wpmm_settings");
    $data['maintenance_mode'] = $mm_options['general']['status'];

//$mm_options['general']['status'] = $maintenance;
//$customization->setOptionValue('wpmm_settings',serialize($mm_options));
    
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
    

    //update theme data
    update_option('blog_public',$source_data['blog_public'],'yes');
    $mm_options['general']['status'] = $source_data['maintenance_mode'];
    update_option('wpmm_settings',$mm_options,'yes');

    $response = new WP_REST_Response($source_data);
    return $response;
}




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

    //update theme data
    update_option('mail_from',$source_data['mail_from'],'yes');
    update_option('mail_from_name',$source_data['mail_from_name'],'yes');
    update_option('smtp_host',$source_data['smtp_host'],'yes');
    update_option('smtp_port',$source_data['smtp_port'],'yes');
    update_option('smtp_user',$source_data['smtp_user'],'yes');
    update_option('smtp_pass',$source_data['smtp_pass'],'yes');
    update_option('smtp_auth',$source_data['smtp_auth'],'yes');
    update_option('smtp_ssl',$source_data['smtp_ssl'],'yes');
    
    $response = new WP_REST_Response($source_data);
    return $response;
}






function sellerlinx_get_tracking_settings(  $data ) {
    $data = array();
    $data['google_analytics'] = get_theme_mod("sellerlinx_google_analytics");
    
    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_update_tracking_settings( $request) {

    $json_input = json_decode(file_get_contents('php://input'));

    $source_data = array();
    $source_data['google_analytics'] = get_theme_mod("sellerlinx_google_analytics");
    if(!is_null($json_input->google_analytics)) $source_data['google_analytics']=$json_input->google_analytics;
    

    //update theme data
    set_theme_mod('sellerlinx_google_analytics',$source_data['google_analytics']);
    
    
    $response = new WP_REST_Response($source_data);
    return $response;
}




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
    
    //$lang_options = get_option("qtranslate_enabled_languages");
//    $source_data['supported']=array();
/*
    if(!is_null($json_input->zh_supported)) $source_data['supported'][]='zh';
    if(!is_null($json_input->en_supported)) $source_data['supported'][]='en';
    if(!is_null($json_input->tw_supported)) $source_data['supported'][]='tw';
*/
    if(!is_null($json_input->supported)) $source_data['supported']=$json_input->supported;
    //$source_data['supported'][]='en';

    update_option('qtranslate_enabled_languages',$source_data['supported'],'yes');

    $lang_options = get_option("qtranslate_default_language");
    $source_data['default']=$lang_options; 
    if(!is_null($json_input->default)) $source_data['default']=$json_input->default;
    
    //update theme data
    update_option('qtranslate_default_language',$source_data['default'],'yes');
    //$source_data['test']=$json_input->zh_supported;

    $response = new WP_REST_Response($source_data);
    return $response;
}







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



function sellerlinx_get_banner_settings(  $data ) {
    $data = array();

    $data['height'] = get_theme_mod("sellerlinx_banner_height");

    $data['size'] = get_theme_mod( 'sellerlinx_banner_size' ); 
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
    $source_data['size'] = get_theme_mod( 'sellerlinx_banner_size' ); 
    if(!is_null($json_input->size)) $source_data['size']=$json_input->size; 
    $source_data['configuration'] = get_theme_mod( 'sellerlinx_banner_configuration' ); 
    if(!is_null($json_input->configuration)) $source_data['configuration']=$json_input->configuration; 
    $source_data['transition'] = get_theme_mod( 'sellerlinx_banner_transition' ); 
    if(!is_null($json_input->transition)) $source_data['transition']=$json_input->transition; 
    $source_data['transition_duration'] = get_theme_mod( 'sellerlinx_banner_transition_duration' ); 
    if(!is_null($json_input->transition_duration)) $source_data['transition_duration']=$json_input->transition_duration; 

    $source_data['banners'] = $json_input->banners;
    
    set_theme_mod("sellerlinx_banner_height", $source_data['height']);
    set_theme_mod("sellerlinx_banner_size", $source_data['size']);
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
        
        $banners[] = $banner;
    }

    update_option('widget_sellerlinx-banner-widget', $banners, yes);
    update_option('sidebars_widgets', $sidebars_widgets_options, yes);

    $response = new WP_REST_Response($source_data);
    return $response;
}




function sellerlinx_get_keys(  $request ) {
    $headers = getallheaders();
    $auth  = explode(" ",$headers['Authorization']);
    $credentials = explode(":",base64_decode($auth[1]));
    $username = $credentials[0];
    $user = get_user_by('slug',$username);
    $data = array(); 
    global $wpdb;
//INSERT INTO `slwp_woocommerce_api_keys` (`key_id`, `user_id`, `description`, `permissions`, `consumer_key`, `consumer_secret`, `nonces`, `truncated_key`, `last_access`) VALUES
//(1, 1, 'Sellerlinx', 'read_write', 'c5b0c70b09d47ca3bdc2df30ba721e3b0b43f25d72ef62a9e5808a1d7718b30d', 'cs_e0d5451bb3d58429cd36b411dc97e76903db5b6c', NULL, '92667c5', '2017-02-23 10:47:16');
    
    $results = $wpdb->get_results( 'SELECT consumer_key, consumer_secret FROM '.$wpdb->prefix.'woocommerce_api_keys WHERE user_id = '.$user->ID.' AND permissions = "read_write"', OBJECT );
    $consumer_secret;
    $consumer_key;
    foreach ( $results as $woo_key ) 
    {
        $consumer_secret = $woo_key->consumer_secret;
        $consumer_key = $woo_key->consumer_key;
    }
    $data['username'] = $username;
    $data['userID'] = $user->ID;
    
    $data['woocommerce_consumer_key']=$consumer_key;
    $data['woocommerce_consumer_secret']=$consumer_secret;
    $data['test']=  wc_api_hash( sanitize_text_field( 'ck_bc6e8e7bb68f664a8fb25ce8097fcffe1407d45c' ) );
    $response = new WP_REST_Response($data);
    return $response;
}

function sellerlinx_auth(){
    $headers = getallheaders();
    
    if(empty($headers['Authorization'])) return false;
    $auth = $headers['Authorization'];
    $parts = explode(" ",$auth);
    
    if($parts[0]!="Basic") return false;
    
    $parts = explode(":",base64_decode($parts[1]));

    $username=$parts[0];
    $password=$parts[1];

    $check = wp_authenticate_username_password(NULL,$username,$password);
    if(is_wp_error($check)) return false;
    
    return true;
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


add_action( 'rest_api_init', function () {
  register_rest_route( 'sellerlinx/v2', 'keys', array(
    array(
        'methods' => 'GET',
        'callback' => 'sellerlinx_get_keys',
        'permission_callback' => 'sellerlinx_auth',
      ),
    )
   );
  
} );



