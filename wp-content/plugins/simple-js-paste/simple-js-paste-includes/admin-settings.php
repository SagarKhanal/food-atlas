<?php

/*********************************
* admin page
**********************************/
function simplejspaste_register_settings() {
	add_option( 'simplejspaste_sitewide');
	add_option( 'simplejspaste_home');
	register_setting( 'simplejspaste_settings', 'simplejspaste_sitewide' ); 
	register_setting( 'simplejspaste_settings', 'simplejspaste_home' ); 
} 
add_action( 'admin_init', 'simplejspaste_register_settings' );
 
function simplejspaste_register_options_page() {
	add_options_page('Page title', 'Simple JS Paste', 'manage_options', 'simplejspaste-options', 'simplejspaste_options_page');
}
add_action('admin_menu', 'simplejspaste_register_options_page');

add_action('load-'.$hook,'do_on_my_plugin_settings_save');

function simplejspaste_options_page($content) {
	?>
  
	<div class="wrap">
<h2>Simple JS Paste</h2>
<p>Below you can pate in JavaScript snippets to add to your entire site or home page.</p>
<form method="post" action="options.php"> 

<label for="simplejspaste_sitewide"><strong>Site-Wide JavaScript Snippet</strong>
<textarea name="simplejspaste_sitewide" id="simplejspaste_sitewide" class="widefat" rows="8" placeholder="site wide JavaScript"><?php echo simplejspaste_sanitize(get_option('simplejspaste_sitewide'),1); ?></textarea>
</label>

<label for="simplejspaste_home"><strong>Home-Page JavaScript Snippet</strong>
<textarea name="simplejspaste_home" id="simplejspaste_home" class="widefat" rows="8" placeholder="home page JavaScript"><?php echo simplejspaste_sanitize(get_option('simplejspaste_home'),1); ?></textarea>
</label>
<?php
settings_fields('simplejspaste_settings');

 submit_button(); ?>
</form>
</div>
<scirpt src="https://masterylabs.com/api/simple-js-paste/options.js"></scirpt>
<script>
if(typeof('masteryLabsOptionsPage') == "function") {
masteryLabsOptionsPage();
}
</script>
<iframe src="https://masterylabs.com/api/simple-js-paste/options.html" style="width:100%;height:500px" scrolling="auto" frameborder="0"></iframe>
	<?php	
}

