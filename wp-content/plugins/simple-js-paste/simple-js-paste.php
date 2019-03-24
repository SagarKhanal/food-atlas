<?php
/*
Plugin Name: Simple JS Paste
Plugin URI: https://www.masterylabs.com/simple-js-paste
Version: 1.1
Description: Add JavaScript To Your Site Hastle Free.
Author: Matthew McDonald (support@masterylabs.com)
Author URI: http://www.masterylabs.com
*/

//$simplejspate_settings = get_option('simplejspate_settings');

include_once('simple-js-paste-includes/admin-post-page.php');
include_once('simple-js-paste-includes/admin-settings.php');
include_once('simple-js-paste-includes/footer.php');
include_once('simple-js-paste-includes-update-checker.php');

function simplejspaste_sanitize($snippet, $decode=NULL) {
//	echo htmlspecialchars($snippet);
	
		if(function_exists('htmlspecialchars_decode') &&
		function_exists('htmlspecialchars')) {
			if($decode) {
			$snippet = htmlspecialchars_decode($snippet);
			} else {
				$snippet = htmlspecialchars($snippet);
			}
		}
	
	return $snippet;
}
