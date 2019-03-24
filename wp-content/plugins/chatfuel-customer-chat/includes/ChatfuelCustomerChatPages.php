<?php

class ChatfuelCustomerChatPages {

	private $baseDomain;

	private $pageIdOption   = 'chtfl-chat-page-id';
	private $pageNameOption = 'chtfl-chat-page-name';

	private $mainPageUrl;

	public function __construct( $domain ) {

		$this->mainPageUrl = add_query_arg( [ 'page' => 'customer-chat' ], admin_url( 'admin.php' ) );

		$this->baseDomain = $domain;
	}

	public function setPageId( $id ) {
		update_option( $this->pageIdOption, $id );
	}

	public function getPageId() {
		return trim( get_option( $this->pageIdOption, '' ) );
	}

	public function setPageName( $id ) {
		update_option( $this->pageNameOption, $id );
	}

	public function getPageName() {
		return trim( get_option( $this->pageNameOption, '' ) );
	}

	public function customerChatPage() {

		$this->processUpdatePageId();

		$page_id = $this->getPageId();

		?>
        <div class="wrap">

            <div class="main-plugin-container">

                <h1><?php _e( 'Chat Settings', 'chatfuel-customer-chat' ); ?></h1>

				<?php if ( empty( $page_id ) ) { ?>

                    <a class="connect-button margin-top" href="<?php
					echo add_query_arg(
						[
							'referrer' => urlencode( $this->mainPageUrl )
						]
						, $this->baseDomain )
					?>"><?php _e( 'Connect Facebook Page', 'chatfuel-customer-chat' ); ?></a>

				<?php } ?>

				<?php if ( ! empty( $page_id ) ) { ?>
                    <p class="howto">
                        <?php _e( 'Please sign into your Facebook Page admin panel to chat with your customers.', 'chatfuel-customer-chat' ); ?>
                    </p>

                    <h1 class="margin-top">
                        <?php _e( 'Your Page Connected:', 'chatfuel-customer-chat' ); ?>
                    </h1>

                    <div class="page-container">

                        <img src="https://customerchatapp.com/assets/images/icon.png">

                        <span class="page-title"><?php echo $this->getPageName(); ?></span>

                        <a class="disconnect-button" href="<?php
						echo add_query_arg( [ 'update_page_id' => true ], $this->mainPageUrl );
						?>"><?php _e( 'Disconnect', 'chatfuel-customer-chat' ); ?></a>

                    </div>
				<?php } ?>

                <h1 class="margin-top"><?php _e( 'Extra Features', 'chatfuel-customer-chat' ); ?></h1>

                <p class="howto">
					<?php echo sprintf( __( 'You can enhance your messenger experience by building a chatbot on %s for free or talk with %s to design and build more sophisticated chatbot logic.', 'chatfuel-customer-chat' ),
					                    '<a target="_blank" href="http://chatfuel.com/">www.chatfuel.com</a>',
					                    '<a target="_blank" href="http://masterofcode.com/">www.masterofcode.com</a>'
					); ?>
                </p>

                <div class="features-row">
                    <div class="feature-block chatfuel">
                        <div class="logo-container">
                        </div>
                        <div class="text-container">

                            <p>
	                            <?php echo sprintf( __( 'Set up automated messaging and custom conversational flows with %s to enhance the customer experience. Sign up for free today.', 'chatfuel-customer-chat' ),
	                                                '<a href="https://chatfuel.com/">Chatfuel</a><sup>TM</sup>'
	                            ); ?>
                            </p>
                            <a target="_blank" href="http://chatfuel.com/" class="feature-button">
	                            <?php _e( 'Sign up for Chatfuel', 'chatfuel-customer-chat' ); ?>
                            </a>
                        </div>
                    </div>

                    <div class="feature-block master-of-code">
                        <div class="logo-container">
                        </div>
                        <div class="text-container">

                            <p>
	                            <?php echo sprintf( __( 'Get a custom Facebook Messenger chatbot built with one of the world leaders in bot development – %s', 'chatfuel-customer-chat' ),
	                                                '<a href="https://masterofcode.com/">Master.of.Code</a>'
	                            ); ?>
                            </p>
                            <a target="_blank" href="http://masterofcode.com/" class="feature-button">
	                            <?php _e( 'Contact Master.of.Code', 'chatfuel-customer-chat' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	private function processUpdatePageId() {

		if ( ! empty( $_REQUEST['update_page_id'] ) ) {

			$this->setPageId( esc_sql( trim( $_REQUEST['page_id'] ) ) );
			$this->setPageName( esc_sql( trim( $_REQUEST['page_name'] ) ) );

			?>
            <script type="text/javascript">
                window.location.href = '<?php echo $this->mainPageUrl; ?>'
            </script>
			<?php

		}
	}

}
