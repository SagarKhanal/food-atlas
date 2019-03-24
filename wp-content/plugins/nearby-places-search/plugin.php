<?php
/**
 * Plugin Name: Nearby Places Search
 * Description: Nearby Places Search by place types with Google Map API.
 * Version: 1
 * Author: Dipankar Biswas
 */

 
/**
 * Include configuration file.
 */
include_once plugin_dir_path( __FILE__ ).'/config/nearby_places_search_config.php';
/**
 * deactivation plugin hook.
 */
 register_deactivation_hook( __FILE__, 'nearby_places_search_deactivate' ); 
 function nearby_places_search_deactivate() {
	delete_option( 'nearby-place-search-plugin-settings' );
 }  
/**
 * Register a new short code for Nearby search form: [nearby_places_search_code]   This is a WP default function.
 */
add_shortcode( 'nearby_places_search_code', 'nearby_places_search_shortcode' );
 
/**
 * Action function for above shortcode
 * @param    Null  
 * @return   Null
 */
function nearby_places_search_shortcode() {
    ob_start();
	$settings = get_option( 'nearby-place-search-plugin-settings');	
	include __DIR__."/view/loadPlace.php";
	return ob_get_clean();
}
/**
 * Register style sheet and script at frontend page.
 */
add_action( 'wp_enqueue_scripts', 'nearby_place_search_front' );
function nearby_place_search_front() {
	global $post;
	if( has_shortcode( $post->post_content, 'nearby_places_search_code') ) {
		$settings = get_option( 'nearby-place-search-plugin-settings');
		wp_register_style( 'css-nearby-place', plugins_url( '/css/nearby_places_search.css', __FILE__ ) );			
		wp_enqueue_style( 'css-nearby-place' );		
		wp_enqueue_script('jquery');		
		wp_enqueue_script('js-nearby-place-library', nearby_places_search_build_api_url(), '', '' , true);		
		wp_register_script( 'js-nearby-place-custom', plugins_url( '/js/nearby-place.js', __FILE__ ), '', '' , true);
		// Localize the script with new data
		$translation_array = array(
			'name' => __( 'Name', 'plugin-domain' ),
			'address' => __( 'Address', 'plugin-domain' ),
			'urllink' => __( plugins_url( '/nearby_places_search_markers', __FILE__ ), 'plugin-domain' ),
			'sorry_text' => __( 'Sorry, no results found.', 'plugin-domain' ),
			'failed_text' => __( 'Geocoder failed due to', 'plugin-domain' ),
			'no_record_text' => __( 'No results found', 'plugin-domain' ),
			'latitude' => $settings['location_latitude'] ? esc_attr( $settings['location_latitude'] ) : DEFAULT_LAT,
			'longitude' => $settings['location_longitude'] ? esc_attr( $settings['location_longitude'] ) : DEFAULT_LAT,
			'radius' => $settings['radius'] ? esc_attr( $settings['radius'] ) : DEFAULT_LAT,
		);
		wp_localize_script( 'js-nearby-place-custom', 'object_name', $translation_array );	
		wp_enqueue_script( 'js-nearby-place-custom' );		
	}
}
/**
 * Builds the javascript maps api url based on authentication method.
 */
function nearby_places_search_build_api_url() {
	// Google api url.
	$api_url = '//maps.googleapis.com/maps/api/js';
	// Array to hold query parameters for the google maps url.
	// Including version number as it's required for Premium plans.
	// https://developers.google.com/maps/documentation/javascript/versions
	$query = array('v' => '3', 'libraries' => 'places');
	$settings = (array) get_option( 'nearby-place-search-plugin-settings' );
	// Add query params to API url.
	if (!empty( $settings['api_key'])) {
		$query['key'] =  $settings['api_key'];
	}
	return add_query_arg( $query, $api_url );
}
/* 
 *  Hook for adding admin menus and form
 */ 
add_action('admin_menu', 'nearby_places_search_menu');
add_action( 'admin_init', 'nearby_places_search_init' );
/* 
 *  Action function for above admin_menu hook
 */ 
function nearby_places_search_menu() {   
    add_options_page(__('Search Settings','nearby-places-search'), __('Search Settings','nearby-places-search'), 'manage_options', 'searchsettings', 'nearby_place_search');	
}
/* 
 *  Action function for above admin_init hook
 */
function nearby_places_search_init() {   	
  	 register_setting( 'nearby-place-search-settings-group', 'nearby-place-search-plugin-settings', 'nearby_place_search_settings_validate_and_sanitize' );	 
  	add_settings_section( 'nearby-group', __( '', 'textdomain' ), '', 'nearby-plugin' );
  	add_settings_field( 'location-type', __( 'Location Types', 'textdomain' ), 'location_type_callback', 'nearby-plugin', 'nearby-group' );
	add_settings_field( 'api-key', __( 'Google Maps API Key ', 'textdomain' ), 'api_key_callback', 'nearby-plugin', 'nearby-group' );	
	add_settings_field( 'location-latitude', __( 'Location : Latitude', 'textdomain' ), 'location_latitude_callback', 'nearby-plugin', 'nearby-group' );
	add_settings_field( 'location-longitude', __( 'Location : Longitude', 'textdomain' ), 'location_longitude_callback', 'nearby-plugin', 'nearby-group' );
	add_settings_field( 'location-name', __( 'Location Name ', 'textdomain' ), 'location_name_callback', 'nearby-plugin', 'nearby-group' );
	add_settings_field( 'radius', __( 'Radius', 'textdomain' ), 'radius_callback', 'nearby-plugin', 'nearby-group' );
}
/* 
 * THE ACTUAL PAGE 
 * nearby_place_search() displays the page content for the Search Settings submenu
 * */
function nearby_place_search() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( 'You do not have sufficient permissions to access this page.' );
    }
?>
  <div class="wrap">
      <h2><?php _e('Nearby Places Search Settings', 'textdomain'); ?></h2>
      <form action="options.php" method="POST">
        <?php settings_fields('nearby-place-search-settings-group'); ?>
        <?php do_settings_sections('nearby-plugin'); ?>
        <?php submit_button(); ?>
      </form>
  </div>
<?php }
/* 
 *  Action Callback function for above add_settings_field hook
 */
function location_type_callback() {
	global $_location_types;	
	$settings = (array) get_option( 'nearby-place-search-plugin-settings' );	
	$field = "location_type";	
	$html = '';		
	foreach($_location_types as $type => $value) {
		if ( is_array( $settings['location_type'] ) && in_array( $type, $settings['location_type'] ) ) {
		$checked = 'checked="checked"';
		} else {
			$checked = null;
		}
		$html .= "<div class='form-item form-type-checkbox'><input type='checkbox' id='$field' name='nearby-place-search-plugin-settings[$field][]' value='$type'" . $checked . '/>';
		$html .= "<label class='option' for='$value'>$value</label></div>";
	}
	echo $html;
}
function api_key_callback() {	
	$settings = (array) get_option( 'nearby-place-search-plugin-settings' );
	$field = "api_key";
	$value = esc_attr( $settings[$field] );	
	echo "<input type='text' class='regular-text ltr' name='nearby-place-search-plugin-settings[$field]' value='$value' />";
	?>
	<p class="description"><?php  _e( "Obtain a Google Maps Javascript API key at <a href='https://developers.google.com/maps/documentation/javascript/get-api-key'>https://developers.google.com/maps/documentation/javascript/get-api-key</a>");
	?></p><?php
}
function location_latitude_callback() {	
	$settings = (array) get_option( 'nearby-place-search-plugin-settings' );
	$field = "location_latitude";
	$value = $settings[$field] ? esc_attr( $settings[$field] ) : DEFAULT_LAT;	
	echo "<input type='text' class='regular-text ltr'  name='nearby-place-search-plugin-settings[$field]' value='$value' />";
	?><p class="description"><?php  _e( "The location's latitude which you wish to search from.");
	?></p><?php
}
function location_longitude_callback() {	
	$settings = (array) get_option( 'nearby-place-search-plugin-settings' );
	$field = "location_longitude";
	$value = $settings[$field] ? esc_attr( $settings[$field] ) : DEFAULT_LONG;	
	echo "<input type='text' class='regular-text ltr'  name='nearby-place-search-plugin-settings[$field]' value='$value' />";
	?><p class="description"><?php  _e( "The location's longitude which you wish to search from.");
	?></p><?php
}
function location_name_callback() {	
	$settings = (array) get_option( 'nearby-place-search-plugin-settings' );
	$field = "location_name";
	$value = $settings[$field] ? esc_attr( $settings[$field] ) : DEFAULT_NMAE;	
	echo "<input type='text' class='regular-text ltr'  name='nearby-place-search-plugin-settings[$field]' value='$value' />";
	?><p class="description"><?php  _e( "The default location name for above latitude and longitude.");
	?></p><?php
}
function radius_callback() {	
	$settings = (array) get_option( 'nearby-place-search-plugin-settings' );
	$field = "radius";
	$value = $settings[$field] ? esc_attr( $settings[$field] ) : DEFAULT_RADIUS;	
	echo "<input type='text' name='nearby-place-search-plugin-settings[$field]' size='5' maxlength='5' value='$value' />";
	?><p class="description"><?php  _e( "The radius in meters, from your search start point. Maximum is 50000.");
	?></p><?php
}
/*
* INPUT VALIDATION callback
* */
function nearby_place_search_settings_validate_and_sanitize( $data ) {		
	if (null == $data['location_latitude'])	{
		add_settings_error(
			'location_latitude',
			'empty',
			'Cannot be empty Location Latitude',
			'error'
		);
	}
	if (null == $data['location_longitude']) {
		add_settings_error(
			'location_longitude',
			'empty',
			'Cannot be empty Location Longitude',
			'error'
		);
	}
	if (null == $data['location_name']) {
		add_settings_error(
			'location_name',
			'empty',
			'Cannot be empty Location Name',
			'error'
		);
	}
	if (null == $data['radius']) {
		add_settings_error(
			'radius',
			'empty',
			'Cannot be empty Radius',
			'error'
		);
	}
	return $data;
}
/**
 * Register style sheet at Admin page.
 */
function nearby_place_search_admin_enqueue_scripts() { 
	if( $_GET['page'] == 'searchsettings') {
		wp_enqueue_style( 'nearby-place-search', plugins_url( 'nearby_place_search/css/nearby_places_search_admin.css' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'nearby_place_search_admin_enqueue_scripts' );