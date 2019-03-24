<?php
/*
Plugin Name: WP Chatbot
Plugin URI:  https://www.holithemes.com/wp-chatbot/
Description: Add Messenger to your website, Chatbot or live Chat using Facebook Messenger
Version:     2.1
Author:      HoliThemes
Author URI:  https://www.holithemes.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-chatbot
*/


if ( ! defined( 'ABSPATH' ) ) exit;


// define HTCC_PLUGIN_FILE
if ( ! defined( 'HTCC_PLUGIN_FILE' ) ) {
	define( 'HTCC_PLUGIN_FILE', __FILE__ );
}

// include main file
require_once 'inc/class-ht-cc.php';

// create instance for the main file - HT_CCW
function ht_cc() {
	return HT_CC::instance();
}

ht_cc();