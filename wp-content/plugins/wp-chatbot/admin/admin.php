<?php
/**
 * Central file for admin 
 * 
 * @package htcc
 * @subpackage Admin
 * @since 1.0.0
 * 
 * subpackage Admin loads only on wp-admin 
 */


if ( ! defined( 'ABSPATH' ) ) exit;

require_once('class-htcc-lang.php');

require_once('class-htcc-admin.php');


require_once('class-htcc-enqueue.php');


$admin = new HTCC_Admin();
add_action('admin_menu',  array( $admin, 'htcc_options_page') );
add_action( 'admin_init', array( $admin, 'htcc_custom_settings' ) );