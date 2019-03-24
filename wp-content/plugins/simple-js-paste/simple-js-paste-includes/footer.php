<?php

function simplejspaste_footer_display($snippet=NULL) {
	if(empty($snippet)) {return false; }

	$snippet = trim(simplejspaste_sanitize($snippet,1));
	
	if(strstr($snippet, '<script>') == true || strstr($snippet, '<script ') == true) {
		echo $snippet;
	} else {
		echo '<script>'.$snippet.'</script>';
	}

}
function simplejspaste_footer() {
	$displayJS['footer'] = '';
	$displayJS['sitewide'] = get_option('simplejspaste_sitewide');
	
	if(trim($displayJS['sitewide']) != '') {
		simplejspaste_footer_display($displayJS['sitewide']);
	}
	
	if(is_home() && get_option('simplejspaste_home')) {
			simplejspaste_footer_display(get_option('simplejspaste_home'));
	}
	else if(is_singular()) {
		$snippet = get_post_meta( get_the_ID(), 'simplejspaste_footer', true);
		if($snippet != '') { simplejspaste_footer_display($snippet); }	
	}
}



add_action('wp_footer', 'simplejspaste_footer');

