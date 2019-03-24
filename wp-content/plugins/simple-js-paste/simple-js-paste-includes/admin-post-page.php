<?php

/*********************************
* post page options
**********************************/


/**
 * Adds a meta box to the post editing screen
 */

function simplejspaste_meta_callback( $post ) {
	$simplejspaste_meta = get_post_meta( $post->ID );
	?>
    
    <h4>Paste In Your JavaScript code or snippet in the box below</h4><p>For a single script, you can enter in your code with or without the script tags (&lt;script&gt;&lt;/script&gt;).<br /> If you want to enter multiple scripts, simple use open and close script tags.)</p>
<textarea name="simplejspaste_footer" id="simplejspaste_footer" class="widefat" rows="8" placeholder="Enter your JavaScript here..."><?php echo simplejspaste_sanitize($simplejspaste_meta['simplejspaste_footer'][0],1); ?></textarea>

<iframe src="https://masterylabs.com/api/simple-js-paste/meta.html" style="width:100%;height:90px;border:solid 0px #FFF" scrolling="no" frameborder="0" seamless></iframe>
<?php
}



function simplejspaste_custom_meta() {
    add_meta_box( 'simplejspaste_meta', __( 'Simple JS Paste', 'simplejspaste-meta-box' ), 'simplejspaste_meta_callback', 'post' );
    add_meta_box( 'simplejspaste_meta', __( 'Simple JS Paste', 'simplejspaste-meta-box' ), 'simplejspaste_meta_callback', 'page' );
}


add_action( 'add_meta_boxes', 'simplejspaste_custom_meta');


/**
 * Saves the custom meta input
 */
function simplejspaste_meta_save($post_id) {
	if(isset($_POST['simplejspaste_footer']) == true) {
			$simplejspaste_footer = $_POST['simplejspaste_footer'];
		} else {
			$simplejspaste_footer = "";
		}
update_post_meta( $post_id, 'simplejspaste_footer', simplejspaste_sanitize( $simplejspaste_footer));
}
add_action( 'save_post', 'simplejspaste_meta_save');

