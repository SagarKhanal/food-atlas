<?php
/**
* shortcodes 
* base shorcode name is [chat]
* for list of attribute support check  -> shortcode_atts ( $a )
*
* @package ccw
* @since 1.0
*/    

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Shortcode' ) ) :
    
class HTCC_Shortcode {

    
    function shortcode($atts = [], $content = null, $shortcode = '') {

        // $htcc_options = get_option('htcc_options');
        $htcc_options = ht_cc()->variables->get_option;
        
    
        $fb_app_id = esc_attr( $htcc_options['fb_app_id'] );
        $fb_page_id = esc_attr( $htcc_options['fb_page_id'] );
        $fb_sdk_lang = esc_attr( $htcc_options['fb_sdk_lang'] );
        $fb_minimized = esc_attr( $htcc_options['minimized'] );
        $fb_ref = esc_attr( $htcc_options['ref'] );
        $fb_color = esc_attr( $htcc_options['fb_color'] );
        $fb_greeting_login = esc_attr( $htcc_options['fb_greeting_login'] );
        $fb_greeting_logout = esc_attr( $htcc_options['fb_greeting_logout'] );


        /**
         * min  - true or false
         */
        $a = shortcode_atts(
            array(
                'app_id' => $fb_app_id,
                'page_id' => $fb_page_id,


                'color' => $fb_color,
                'logged_in_greetings' => $fb_greeting_login,
                'logged_out_greetings' => $fb_greeting_logout,
                

                'min' => $fb_minimized,
                'ref' => $fb_ref,

            ), $atts, $shortcode );


        $app_id = $a["app_id"];
        $page_id = $a["page_id"];
        $fb_color = $a["color"];
        $fb_greeting_login = $a["logged_in_greetings"];
        $fb_greeting_logout = $a["logged_out_greetings"];
        $min = $a["min"];
        $ref = $a["ref"];


        // as now hide based on device not implemented for shortcodes
        // // $is_mobile = $GLOBALS["htcc_isMob"];
        // $is_mobile = ht_cc()->device_type->is_mobile;
        // if ( 'yes' == $is_mobile ) {
        //     if ( isset( $htcc_options['hideon_mobile'] ) ) {
        //         return;
        //     }
        // } else {
        //     if ( isset( $htcc_options['hideon_desktop'] ) ) {
        //         return;
        //     }
        // }


        $o = '';
        $o .= "<script>
        window.fbAsyncInit = function() {
          FB.init({
            appId            : $app_id,
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v2.12'
          });
        };
      
        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = 'https://connect.facebook.net/$fb_sdk_lang/sdk.js';
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
      </script>";


        $o .= '';
        $o .= '<div class="htcc-messenger">
        <div class="fb-customerchat"
        page_id="'.$page_id.'" 
        theme_color="' .$fb_color. '" 
        logged_in_greeting="' .$fb_greeting_login. '" 
        logged_out_greeting="' .$fb_greeting_logout. '" 
        ref="'.$ref.'"
        minimized="'.$min.'">
        </div>
      </div>';
        $o .= '';
        

        return $o;
    }


    //  Register shortcode
    function htcc_shortcodes_init() {

        $htcc_options = get_option('htcc_options');
        
        $shortcode_name = esc_attr( $htcc_options['shortcode'] );
        
        // add_shortcode('chatbot', array( $this, 'shortcode' ));
        add_shortcode( $shortcode_name, array( $this, 'shortcode' ) );
    }


}

$shortcode = new HTCC_Shortcode();

add_action('init', array( $shortcode, 'htcc_shortcodes_init' ) );

endif; // END class_exists check