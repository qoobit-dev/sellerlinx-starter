<?php
/**
 * Main Functions file
 *
 * @since 	1.0.0
 * @package WPFBS
 */


// Hook the fb SDK to wp_head.
add_action( 'wp_head', 'wpfbs_fb_sdk' );

function wpfbs_fb_sdk(){
	global $settings_arr;
	if ( 1 == $settings_arr['wpfbs_enable_button'] || shortcode_exists( 'wpfbs' ) ) {
		?>
		<style>
			.wpfbs_btn_margin {
			    margin-bottom: 1em;
			    margin-top: 1em;
			}
		</style>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=350361131652532";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	<?php
	} // if ended.
}

// Add Fb Save button to post/page content.
add_filter( 'the_content', 'wpfbs_add_send_button' );
function wpfbs_add_send_button($content){
	global $settings_arr;
	$wpfbs_btn_markup = '';

	// For Pages
	if( (is_page() && $settings_arr['wpfbs_display_on'] == 'pages') || ($settings_arr['wpfbs_display_on'] == 'both') ){

		if( $settings_arr['wpfbs_button_location'] == 'before_content'){

			//Before Content
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';
			$wpfbs_btn_markup .= $content;

		}else if($settings_arr['wpfbs_button_location'] == 'after_content'){

			//Before Content
			$wpfbs_btn_markup .= $content;
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';

		}else{

			//for both locations
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';
			$wpfbs_btn_markup .= $content;
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';
			
		}

	} // for pages end


	// For Posts
	if( (is_single() && $settings_arr['wpfbs_display_on'] == 'posts') || ($settings_arr['wpfbs_display_on'] == 'both') ){

		if( $settings_arr['wpfbs_button_location'] == 'before_content'){

			//Before Content
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';
			$wpfbs_btn_markup .= $content;

		}else if($settings_arr['wpfbs_button_location'] == 'after_content'){

			//Before Content
			$wpfbs_btn_markup .= $content;
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';

		}else{

			//for both locations
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';
			$wpfbs_btn_markup .= $content;
			$wpfbs_btn_markup .= '<div id="fb-root"></div>';
			$wpfbs_btn_markup .= '<div class="fb-send wpfbs_btn_margin" data-href="'.get_permalink().'" data-size="small"></div>';
			
		}

	} // for posts end


	//return $wpfbs_btn_markup;
	return $wpfbs_btn_markup ? $wpfbs_btn_markup : $content; 
}


function wpfbs_btn_shortcode() {
	add_shortcode( 'wpfbs', function ( $atts ) {
		// Default Attributes.
		$args = shortcode_atts( array(
		        'link' => get_bloginfo( 'wpurl' )
		    ), $atts );

		$btn_link = $args['link'];
		return '<div id="fb-root"></div><div class="fb-send wpfbs_btn_margin" data-href="'.$btn_link.'" data-size="small"></div>';

	} ); //function and action end.
	
}

// Register the shortcode [wpfbs]
add_action( 'init', 'wpfbs_btn_shortcode' );