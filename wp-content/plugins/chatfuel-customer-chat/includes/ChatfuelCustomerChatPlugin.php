<?php

include "ChatfuelCustomerChatPages.php";

class ChatfuelCustomerChatPlugin {

	private $pages;

	private $appId = '722013358187209';

	private $domain = 'https://customerchatapp.com/';

	private $hooks = [];

	public function __construct() {

		load_plugin_textdomain( 'chatfuel-customer-chat', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		$this->addAllActions();

		$this->pages = new ChatfuelCustomerChatPages( $this->domain );
	}

	private function addAllActions() {
		add_action( 'wp_footer', [ $this, 'onRenderFooter' ] );
		add_action( 'admin_menu', [ $this, 'adminMenu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'includeStyles' ] );
	}

	public function adminMenu() {

		$this->hooks[] = add_menu_page(
			__( 'Chatfuel Customer Chat', 'chatfuel-customer-chat' ), __( 'Chatfuel Customer Chat', 'chatfuel-customer-chat' ), 'manage_options', 'customer-chat',
			array(
				$this->pages,
				'customerChatPage'
			), 'dashicons-format-chat', 100 );

		$this->hooks[] = add_submenu_page(
			'customer-chat', __( 'Chatfuel Customer Chat Settings', 'chatfuel-customer-chat' ), __( 'Chatfuel Customer Chat Settings', 'chatfuel-customer-chat' ), 'manage_options', 'customer-chat',
			array(
				$this->pages,
				'customerChatPage'
			) );

	}

	public function includeStyles($hook) {
		if(in_array($hook, $this->hooks)) {
			wp_enqueue_style( 'customerchat-admin-part-css', CHTFL_CUST_CHAT_URL . "assets/css/customerchat.css" );
		}
	}

	public function slugify($text)
	{
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
//		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w\d]+~', '', $text);
//		$text = preg_replace('[^\d\w]+', '-', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);
		if (empty($text)) {
			return 'n-a';
		}
		return $text;
	}

	public function getRef()
	{
		global $post;

		if(is_front_page()){
			return 'front-page';
		}

		if(is_404()){
			return 'not-found';
		}

		$title = wp_title('', false, 'right');
		$slugified = $this->slugify($title);

		if($slugified == 'n-a'){
			if(is_single() || is_page()){
				return $post->post_type . '-'. $post->ID;
			}
			if(is_tax() || is_tag() || is_category()){
				$term = get_queried_object();
				return $term->taxonomy . '-'. $term->term_id;
			}
		}


		return $slugified;
	}

	function onRenderFooter() {

		$page_id = $this->pages->getPageId();

		$page_ref = $this->getRef();

		echo '<pre style="display: none">'.print_r($page_ref, true).'</pre>';

		if ( ! empty( $page_id ) ) {

			echo "		
				<script type='text/javascript'>
				window.fbAsyncInit = function() {
				    FB.init({
				        appId            : '" . $this->appId . "',
				        autoLogAppEvents : true,
				        xfbml            : true,
				        version          : 'v2.11'
				    });
				};
				
				(function(d, s, id){
				    var js, fjs = d.getElementsByTagName(s)[0];
				    if (d.getElementById(id)) {return;}
				    js = d.createElement(s); js.id = id;
				    js.src = 'https://connect.facebook.net/en_US/sdk.js';
				    fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
				
				
				</script>
				
				<div class='fb-customerchat'
				   page_id='" . $page_id . "'
				   ref='" . $page_ref . "'>
				</div>
			";
		}
	}
}
