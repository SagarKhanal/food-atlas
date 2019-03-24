<?php
/**
 * Database - values
 * plugin details
 * plugin settings - options page
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_db' ) ) :

class HTCC_db {


    /**
     * Add plugin Details to db - wp_options table
     * Add plugin version to db - useful while updating plugin
     * 
     * @uses class-htcc-register -> activate()
     * @return void
     */
    public static function db_plugin_details() {

        // plugin details 
        $plugin_details = array(
            'version' => HTCC_VERSION,
        );

        // Always use update_option - override new values .. don't preseve already existing values
        update_option( 'htcc_plugin_details', $plugin_details );
    }




    /**
     * options page - default values.
     * 
     * @uses class-htcc-register -> activate()
     * @return void
     */
    public static function db_default_values() {

        /**
         * plugin details 
         * 
         * @key enable - 1, means true. show the button.
         */
        $values = array(
            'enable' => '1',
            'fb_app_id' => '',
            'fb_page_id' => '',
            'fb_sdk_lang' => 'en_US',

            'fb_color' => '',
            'fb_greeting_login' => '',
            'fb_greeting_logout' => '',
            
            'list_hideon_pages' => '',
            'list_hideon_cat' => '',
            'shortcode' => 'chatbot',

            'minimized' => '',
            'ref' => '',
        );

        
        // update_option( 'htcc_options', $values );
        // add_option( 'htcc_options', $values );

        $db_values = get_option( 'htcc_options', array() );
        $update_values = array_merge($values, $db_values);
        update_option('htcc_options', $update_values);
    }



}

endif; // END class_exists check