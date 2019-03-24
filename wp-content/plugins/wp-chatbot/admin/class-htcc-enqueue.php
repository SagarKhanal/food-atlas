<?php
/**
 * enqueue sytle, scripts
 * 
 */


if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Enqueue' ) ) :

class HTCC_Enqueue {


    function enqueue( $hook ) {

        if( 'toplevel_page_wp-chatbot' == $hook ) {

            wp_enqueue_style( 'wp-color-picker' );

            wp_enqueue_script( 'htcc_js', plugins_url( 'assets/js/admin.js', HTCC_PLUGIN_FILE ), array( 'wp-color-picker' ), HTCC_VERSION, true );


        }

    }

}

$htcc_enqueue = new HTCC_Enqueue();

add_action('admin_enqueue_scripts', array( $htcc_enqueue, 'enqueue' ) );


endif; // END class_exists check