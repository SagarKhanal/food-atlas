<?php 
/**
 * check condtions to display messenger or not
 * get app id
 * get page id
 * and add it to script, div
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Chatbot' ) ) :

class HTCC_Chatbot {

  public static function chatbot() {

    // $htcc_options = get_option('htcc_options');
    $htcc_options = ht_cc()->variables->get_option;

    
    $enable = esc_attr( $htcc_options['enable'] );
    $fb_app_id = esc_attr( $htcc_options['fb_app_id'] );
    $fb_page_id = esc_attr( $htcc_options['fb_page_id'] );
    $fb_sdk_lang = esc_attr( $htcc_options['fb_sdk_lang'] );
    $fb_minimized = esc_attr( $htcc_options['minimized'] );
    $fb_ref = esc_attr( $htcc_options['ref'] );
    $fb_color = esc_attr( $htcc_options['fb_color'] );
    $fb_greeting_login = esc_attr( $htcc_options['fb_greeting_login'] );
    $fb_greeting_logout = esc_attr( $htcc_options['fb_greeting_logout'] );
    $shortcode_name = esc_attr( $htcc_options['shortcode'] );
    
    /**
     * enable not equal to 1, means dont show the chat button.
     * so retun out of the page.
     */
    if ( '1' !== $enable ) {
      return;
    }
    
    // Hide based on Devices - Mobile, Desktop
    // $is_mobile = $GLOBALS["htcc_isMob"];
    $is_mobile = ht_cc()->device_type->is_mobile;
    
    if ( 'yes' == $is_mobile ) {
        if ( isset( $htcc_options['hideon_mobile'] ) ) {
            return;
        }
    } else {
        if ( isset( $htcc_options['hideon_desktop'] ) ) {
            return;
        }
    }

    
    if ( is_single() && isset( $htcc_options['hideon_posts'] ) ) {
        return;
    }
    
    if ( is_page() && isset( $htcc_options['hideon_page'] ) ) {
        return;
    }
    
    if ( is_home() && isset( $htcc_options['hideon_homepage'] ) ) {
        return;
    }
    
    if ( is_front_page() && isset( $htcc_options['hideon_frontpage'] ) ) {
        return;
    }
    
    if ( is_category() && isset( $htcc_options['hideon_category'] ) ) {
        return;
    }
    
    if ( is_archive() && isset( $htcc_options['hideon_archive'] ) ) {
        return;
    }
    
    if ( is_404() && isset( $htcc_options['hideon_404'] ) ) {
        return;
    } 


    $this_page_id = get_the_ID();
    $pages_list_tohide = $htcc_options['list_hideon_pages'];
    $pages_list_tohide_array = explode(',', $pages_list_tohide);
    
    if( ( is_single() || is_page() ) && in_array( $this_page_id, $pages_list_tohide_array ) ) {
        return;
    }

    // Hide styles on this catergorys - list
    $list_hideon_cat = $htcc_options['list_hideon_cat'];

    if( $list_hideon_cat ) {
        //  Get current post Categorys list and create an array for that..
        $current_categorys_array = array();
        $current_categorys = get_the_category();
        foreach ( $current_categorys as $category ) {
            $current_categorys_array[] = strtolower($category->name);
        }
        
        $list_hideon_cat_array = explode(',', $list_hideon_cat);
        
        foreach ( $list_hideon_cat_array as $category ) {
            $category_trim = trim($category);
            if ( in_array( strtolower($category_trim), $current_categorys_array ) ) {
                return;
            }
        }
    }
    

    // if shortcode added in post, then dont add default one ( this one )
    global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $shortcode_name ) ) {
		return;
	}
    


    $fb_sdk_src = "https://connect.facebook.net/$fb_sdk_lang/sdk.js";

  ?>
    
    
    <!-- Add Messenger - wp-chatbot - HoliThemes - https://holithemes.com/ -->    
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId            : '<?php echo $fb_app_id ?>',
          autoLogAppEvents : true,
          xfbml            : true,
          version          : 'v2.12'
        });
      };
    
      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = '<?php echo $fb_sdk_src ?>';
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
    
    
    <div class="htcc-messenger">
      <div class="fb-customerchat" 
      page_id="<?php echo $fb_page_id ?>"
      theme_color="<?php echo $fb_color ?>" 
      logged_in_greeting="<?php echo $fb_greeting_login ?>" 
      logged_out_greeting="<?php echo $fb_greeting_logout ?>" 
      ref="<?php echo $fb_ref ?>" 
      minimized="<?php echo $fb_minimized ?>">
      </div>
    </div>
    <!-- / Add Messenger - wp-chatbot - HoliThemes -->    


  <?php
  }
}



$chatbot = new HTCC_Chatbot();
add_action( 'wp_footer', array( $chatbot, 'chatbot' ) );


endif; // END class_exists check