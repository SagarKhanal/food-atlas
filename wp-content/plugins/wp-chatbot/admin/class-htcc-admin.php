<?php 
/**
 * Creates top level menu
 * and options page 
 * 
 * @package htcc
 * @subpackage admin
 * @since 1.0.0
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Admin' ) ) :

class HTCC_Admin {


    /**
     * Adds top level menu -> WP CSS Shapes
     *
     * @uses action hook - admin_menu
     * 
     * @since 1.0.0
     * @return void
     */
    public function htcc_options_page() {
        add_menu_page(
            'WP-Chatbot Setting page',
            'WP-Chatbot',
            'manage_options',
            'wp-chatbot',
            array( $this, 'settings_page' ),
            'dashicons-format-chat'
        );
    }


    /**
     * Options page Content - 
     *   get settings form from a template settings_page.php
     * 
     * Call back from - $this->htcc_options_page, add_menu_page
     *
     * @since 1.0.0
     * @return void
     */
    public function settings_page() {
        
        if ( ! current_user_can('manage_options') ) {
            return;
        }

        // get options page form
        require_once('settings_page.php'); 
    }



    /**
     * Options page - Regsiter, add section and add setting fields
     *
     * @uses action hook - admin_init
     * 
     * @since 1.0.0
     * @return void
     */
    public function htcc_custom_settings() {

        register_setting( 'htcc_settings_group', 'htcc_options' , array( $this, 'htcc_options_sanitize' ) );
        
        add_settings_section( 'htcc_settings', '', array( $this, 'htcc_settings_section_cb' ), 'htcc_options_settings' );
        
        add_settings_field( 'enable', __( 'Enable' , 'wp-chatbot' ), array( $this, 'htcc_enable_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_fb_app_id', __( 'Facebook App ID' , 'wp-chatbot' ), array( $this, 'htcc_fb_app_id_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_fb_page_id', __( 'Facebook Page ID' , 'wp-chatbot' ), array( $this, 'htcc_fb_page_id_cb' ), 'htcc_options_settings', 'htcc_settings' );


        add_settings_field( 'htcc_fb_color', __( 'Color' , 'wp-chatbot' ), array( $this, 'htcc_fb_color_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_fb_greeting_login', __( 'Logged in Greeting' , 'wp-chatbot' ), array( $this, 'htcc_fb_greeting_login_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_fb_greeting_logout', __( 'Logged out Greeting' , 'wp-chatbot' ), array( $this, 'htcc_fb_greeting_logout_cb' ), 'htcc_options_settings', 'htcc_settings' );

        
        add_settings_field( 'htcc_show_hide', __( 'Hide Based on post type' , 'wp-chatbot' ), array( $this, 'htcc_show_hide_post_types_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_list_id_tohide', __( 'Post, Page Id\'s to Hide' , 'wp-chatbot' ), array( $this, 'htcc_list_id_tohide_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_list_cat_tohide', __( 'Categorys to Hide' , 'wp-chatbot' ), array( $this, 'htcc_list_cat_tohide_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_devices_show_hide', __( 'Hide Based on Devices' , 'wp-chatbot' ), array( $this, 'htcc_show_hide_devices_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_shortcode', __( 'Shortcode name' , 'wp-chatbot' ), array( $this, 'htcc_custom_shortcode_cb' ), 'htcc_options_settings', 'htcc_settings' );
        
        add_settings_field( 'htcc_fb_sdk_lang', __( 'Messenger language' , 'wp-chatbot' ), array( $this, 'htcc_fb_sdk_lang_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_fb_is_minimized', __( 'Minimized' , 'wp-chatbot' ), array( $this, 'htcc_fb_is_minimized_cb' ), 'htcc_options_settings', 'htcc_settings' );
        add_settings_field( 'htcc_fb_ref', __( 'Ref' , 'wp-chatbot' ), array( $this, 'htcc_fb_ref_cb' ), 'htcc_options_settings', 'htcc_settings' );
        
    }

    // section heading
    function htcc_settings_section_cb() {
        echo '<h1>WP-Chatbot Settings</h1>';
    }

    // enable
    public function htcc_enable_cb() {
        $enable = get_option('htcc_options');
        ?>
        <div>
            <select name="htcc_options[enable]" class="select-1">
            <option value="no"><?php _e( 'No' , 'wp-chatbot' ) ?></option>
            <option value="1" <?php echo esc_attr( $enable['enable'] ) == '1' ? 'SELECTED' : ''; ?>  ><?php _e( 'Yes' , 'wp-chatbot' ) ?></option>
            </select>
        </div>
        <?php
    }

    // App id
    public function htcc_fb_app_id_cb() {

        $htcc_fb_app_id = get_option('htcc_options');
        ?>
        <input type="text" name="htcc_options[fb_app_id]" id="" value="<?php echo esc_attr( $htcc_fb_app_id['fb_app_id'] ) ?>">

        <p class="description"><?php _e( 'Facebook App ID - ' , 'wp-chatbot' ) ?> <a target="_blank" href="https://holithemes.com/wp-chatbot/facebook-app-id/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }


    // page id
    public function htcc_fb_page_id_cb() {

        $htcc_fb_page_id = get_option('htcc_options');
        ?>
        <input type="text" name="htcc_options[fb_page_id]" id="" value="<?php echo esc_attr( $htcc_fb_page_id['fb_page_id'] ) ?>">


        <p class="description"><?php _e( 'Facebook Page ID - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://holithemes.com/wp-chatbot/find-facebook-page-id/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }



    // color
    public function htcc_fb_color_cb() {

        $htcc_fb_color = get_option('htcc_options');
        ?>
        <input name="htcc_options[fb_color]" value="<?php echo esc_attr( $htcc_fb_color['fb_color'] ) ?>" type="text" class="htcc-color-wp" style="height: 1.375rem;" >



        <p class="description"><?php _e( 'messenger theme color , leave empty for default color - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://holithemes.com/wp-chatbot/messenger-theme-color/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }



    // Greeting for logged in user
    public function htcc_fb_greeting_login_cb() {

        $htcc_fb_greeting_login = get_option('htcc_options');
        ?>
        <input type="text" name="htcc_options[fb_greeting_login]" id="" value="<?php echo esc_attr( $htcc_fb_greeting_login['fb_greeting_login'] ) ?>">


        <p class="description"><?php _e( 'Greetings text for fb logged in user * , leave empty for default message - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://holithemes.com/wp-chatbot/change-facebook-messenger-greetings-text/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }

    // Greeting for logged out user
    public function htcc_fb_greeting_logout_cb() {

        $htcc_fb_greeting_logout = get_option('htcc_options');
        ?>
        <input type="text" name="htcc_options[fb_greeting_logout]" id="" value="<?php echo esc_attr( $htcc_fb_greeting_logout['fb_greeting_logout'] ) ?>">


        <p class="description"><?php _e( 'Greetings text for fb logged out user * , leave empty for default message - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://holithemes.com/wp-chatbot/change-facebook-messenger-greetings-text/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }




    // sdk lang. / messenger lang
    public function htcc_fb_sdk_lang_cb() {

        $sdk_lang = get_option('htcc_options');
        $lang = esc_attr( $sdk_lang['fb_sdk_lang'] );
        ?>
        <div>
            <select name="htcc_options[fb_sdk_lang]">
            <?php 
            $fb_lang = HTCC_Lang::$fb_lang;

            foreach ( $fb_lang as $key => $value ) {
            ?>
            <option value="<?php echo $key ?>" <?php echo $lang == $key ? 'SELECTED' : ''; ?> ><?php echo $value ?></option>
            <?php
            }

            ?>
            </select>
        </div>
        <p class="description"><?php _e( 'Language what display in chat window, not user input - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://holithemes.com/wp-chatbot/messenger-language/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <p class="description"><?php _e( 'Facebook SDK is not supporting all languages.., please dont consider it, as an error ' , 'wp-chatbot' ) ?> </p>
        <p class="description"><?php _e( 'If desired Language is not added - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://www.messenger.com/t/1541811499235090/"><?php _e( 'please message us' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }

    // minimized 
    public function htcc_fb_is_minimized_cb() {
        $minimized = get_option('htcc_options');
        $min_value = esc_attr( $minimized['minimized'] );
        ?>
        <div>
            <select name="htcc_options[minimized]" class="select-1">
            <option value="" <?php echo $min_value == "" ? 'SELECTED' : ''; ?> >Default</option>
            <option value="false" <?php echo $min_value == "false" ? 'SELECTED' : ''; ?> >False</option>
            <option value="true" <?php echo $min_value == "true" ? 'SELECTED' : ''; ?> >True</option>
            </select>
        </div>
        <p class="description"><?php _e( 'If true - chat window is minimized' , 'wp-chatbot' ) ?> </p>
        <p class="description"><?php _e( '( Initial time only, user can minimize or not for later visits, facebook sdk handle this )  - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://holithemes.com/wp-chatbot/minimize-messenger/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }

    // ref 
    public function htcc_fb_ref_cb() {

        $reference = get_option('htcc_options');
        ?>
        <input type="text" name="htcc_options[ref]" id="" value="<?php echo esc_attr( $reference['ref'] ) ?>">

        <p class="description"><?php _e( 'WebHook Param ( this is optional, only use this if you know how it works ) - ' , 'wp-chatbot' ) ?><a target="_blank" href="https://holithemes.com/wp-chatbot/messenger-ref/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }

    // checkboxes - based on Type of posts .. 
    public function htcc_show_hide_post_types_cb() {
        $htcc_checkbox = get_option('htcc_options');
        
        // Single Posts
        if ( isset( $htcc_checkbox['hideon_posts'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_posts]" type="checkbox" value="1" <?php checked( $htcc_checkbox['hideon_posts'], 1 ); ?> id="filled-in-box1" />
                <label for="filled-in-box1"><?php _e( 'Hide on - Posts' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_posts]" type="checkbox" value="1" id="filled-in-box1" />
                <label for="filled-in-box1"><?php _e( 'Hide on - Posts' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }


        // Page
        if ( isset( $htcc_checkbox['hideon_page'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_page]" type="checkbox" value="1" <?php checked( $htcc_checkbox['hideon_page'], 1 ); ?> id="filled-in-box2" />
                <label for="filled-in-box2"><?php _e( 'Hide on - Pages' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_page]" type="checkbox" value="1" id="filled-in-box2" />
                <label for="filled-in-box2"><?php _e( 'Hide on - Pages' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }


        // Home Page
        if ( isset( $htcc_checkbox['hideon_homepage'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_homepage]" type="checkbox" value="1" <?php checked( $htcc_checkbox['hideon_homepage'], 1 ); ?> id="filled-in-box3" />
                <label for="filled-in-box3"><?php _e( 'Hide on - Home Page' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_homepage]" type="checkbox" value="1" id="filled-in-box3" />
                <label for="filled-in-box3"><?php _e( 'Hide on - Home Page' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }


        /* Front Page
         A front page is also a home page, but home page is not a front page
         if front page unchecked - it works on both homepage and fornt page
         but if home page is unchecked - it works only on home page, not on front page */
         if ( isset( $htcc_checkbox['hideon_frontpage'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_frontpage]" type="checkbox" value="1" <?php checked( $htcc_checkbox['hideon_frontpage'], 1 ); ?> id="filled-in-box4" />
                <label for="filled-in-box4"><?php _e( 'Hide on - Front Page' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_frontpage]" type="checkbox" value="1" id="filled-in-box4" />
                <label for="filled-in-box4"><?php _e( 'Hide on - Front Page' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }



        // Category
        if ( isset( $htcc_checkbox['hideon_category'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_category]" type="checkbox" value="1" <?php checked( $htcc_checkbox['hideon_category'], 1 ); ?> id="filled-in-box5" />
                <label for="filled-in-box5"><?php _e( 'Hide on - Category' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_category]" type="checkbox" value="1" id="filled-in-box5" />
                <label for="filled-in-box5"><?php _e( 'Hide on - Category' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }



        // Archive
        if ( isset( $htcc_checkbox['hideon_archive'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_archive]" type="checkbox" value="1" <?php checked( $htcc_checkbox['hideon_archive'], 1 ); ?> id="filled-in-box6" />
                <label for="filled-in-box6"><?php _e( 'Hide on - Archive' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_archive]" type="checkbox" value="1" id="filled-in-box6" />
                <label for="filled-in-box6"><?php _e( 'Hide on - Archive' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }



        // 404 Page
        if ( isset( $htcc_checkbox['hideon_404'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_404]" type="checkbox" value="1" <?php checked( $htcc_checkbox['hideon_404'], 1 ); ?> id="filled-in-box7" />
                <label for="filled-in-box7"><?php _e( 'Hide on - 404 Page' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_404]" type="checkbox" value="1" id="filled-in-box7" />
                <label for="filled-in-box7"><?php _e( 'Hide on - 404 Page' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }
        ?>
        <p class="description"> <?php _e( 'check to Hide' , 'wp-chatbot' ) ?> <br> <?php _e( 'Hide Messenger - based on type of the page' , 'wp-chatbot' ) ?> <a target="_blank" href="https://holithemes.com/wp-chatbot/show-hide-messenger-based-on-type-of-the-page/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>


        <?php
    }


    // ID 's list to hide styles
    function htcc_list_id_tohide_cb() {
        $htcc_list_id_tohide = get_option('htcc_options');
        ?>
            <input name="htcc_options[list_hideon_pages]" value="<?php echo esc_attr( $htcc_list_id_tohide['list_hideon_pages'] ) ?>" id="htcc_list_id_tohide" type="text">
            <p class="description"> <?php _e( 'Add Post, Page, Media - ID\'s to hide,' , 'wp-chatbot' ) ?> <br> <?php _e( 'can add multiple id\'s separate with comma ( , )' , 'wp-chatbot' ) ?> - <a target="_blank" href="https://holithemes.com/wp-chatbot/hide-messenger-based-on-post-id/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }

    //  Categorys list - to hide
    function htcc_list_cat_tohide_cb() {
        $htcc_list_cat_tohide = get_option('htcc_options');
        ?>
            <input name="htcc_options[list_hideon_cat]" value="<?php echo esc_attr( $htcc_list_cat_tohide['list_hideon_cat'] ) ?>" id="htcc_list_cat_tohide" type="text" >
            <p class="description"> <?php _e( 'Category name\'s to hide,' , 'wp-chatbot' ) ?> <br> <?php _e( 'can add multiple Categories separate with comma ( , )' , 'wp-chatbot' ) ?> - <a target="_blank" href="https://holithemes.com/wp-chatbot/hide-messenger-based-on-category/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
        <?php
    }




        // checkboxes - based on Type of posts .. 
    public function htcc_show_hide_devices_cb() {
        $htcc_devices = get_option('htcc_options');
        
        // Hide on Mobile Devices
        if ( isset( $htcc_devices['hideon_mobile'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_mobile]" type="checkbox" value="1" <?php checked( $htcc_devices['hideon_mobile'], 1 ); ?> id="hideon_mobile" />
                <label for="hideon_mobile"><?php _e( 'Hide on - Mobile Devices' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_mobile]" type="checkbox" value="1" id="hideon_mobile" />
                <label for="hideon_mobile"><?php _e( 'Hide on - Mobile Devices' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }


        // Hide on Desktop Devices
        if ( isset( $htcc_devices['hideon_desktop'] ) ) {
            ?>
            <p>
                <input name="htcc_options[hideon_desktop]" type="checkbox" value="1" <?php checked( $htcc_devices['hideon_desktop'], 1 ); ?> id="hideon_desktop" />
                <label for="hideon_desktop"><?php _e( 'Hide on - Desktops' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <input name="htcc_options[hideon_desktop]" type="checkbox" value="1" id="hideon_desktop" />
                <label for="hideon_desktop"><?php _e( 'Hide on - Desktops' , 'wp-chatbot' ) ?></label>
            </p>
            <?php
        }
    }


    //  Custom shortcode
    function htcc_custom_shortcode_cb() {
        $htcc_shortcode = get_option('htcc_options');
        ?>
        <div class="row">
            <div class="input-field col s12">
                <input name="htcc_options[shortcode]" value="<?php echo esc_attr( $htcc_shortcode['shortcode'] ) ?>" id="shortcode" type="text" class="validate input-margin">
                <?php
                $shorcode_list = '';
                foreach ($GLOBALS['shortcode_tags'] AS $key => $value) {
                   $shorcode_list .= $key . ', ';
                 }
                ?>
                <p class="description"> <?php printf( __( 'Default values is \'%1$s\', can customize shortcode name' , 'wp-chatbot' ), 'chatbot' ) ?> - <a target="_blank" href="https://holithemes.com/wp-chatbot/change-shortcode-name/"><?php _e( 'more info' , 'wp-chatbot' ) ?></a> </p>
                <p class="description"> <?php _e( 'please dont add this already existing shorcode names' , 'wp-chatbot' ) ?> - <?php echo $shorcode_list ?> </p>
            </div>
        </div>
        <?php
    }



    /**
     * Sanitize each setting field as needed
     *
     * @since 1.0.0
     * @param array $input Contains all settings fields as array keys
     */
    public function htcc_options_sanitize( $input ) {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'not allowed to modify - please contact admin ' );
        }

        $new_input = array();

        foreach ($input as $key => $value) {
            if( isset( $input[$key] ) ) {
                $new_input[$key] = sanitize_text_field( $input[$key] );
            }
        }


        return $new_input;
    }






}

endif; // END class_exists check