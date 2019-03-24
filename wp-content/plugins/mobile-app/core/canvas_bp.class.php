<?php
if (!defined( 'CANVAS_DIR' )) {
	die();
}

class CanvasBp {

	static public function init() {
		add_action( 'bp_notification_after_save', array( 'CanvasBp', 'bp_notification_after_save'));
		if (self::option_on( 'bp_private_messages' )) {
			add_action( 'messages_message_after_save', array( 'CanvasBp', 'messages_message_after_save'));
		}
		if (self::option_on( 'bp_global_messages' )) {
			add_action( 'messages_notice_after_save', array( 'CanvasBp', 'messages_notice_after_save'));
		}
	}

	/**
	* Sent notifications using push notifications
	*
	* @param BP_Notifications_Notification $notification
	*/
	static public function bp_notification_after_save($notification) {
		if (( 'messages' == $notification->component_name) && ('new_message' == $notification->component_action)) {
			return; // handler at other place
		}
		if (( 'friends' == $notification->component_name)) {
			if (!self::option_on( 'bp_friends' )) {
				return;
			}
		} elseif (!self::option_on( 'bp_other_notitications' )) {
			return;
		}

		$push_api = self::get_push_api();

		$title = self::notification_title($notification);
		$text = self::notification_description($notification);
		$url = self::extract_url($text);

		self::normalize_title_and_text($title, $text);
		if (( 'activity' == $notification->component_name) && ( 'new_at_mention' == $notification->component_action)) {
			// link to the mention
			$url = trailingslashit(bp_core_get_userlink($notification->user_id, false, true)) . 'activity/mentions/#acomment-' . $notification->item_id;
		} elseif (( 'friends' == $notification->component_name) && ( 'friendship_request' == $notification->component_action)) {
			// link to friendship messages at the receiver's account. It will show accept link.
			$url = trailingslashit(bp_core_get_userlink($notification->user_id, false, true)) . bp_get_friends_slug() . '/requests/?new';
		}

		$users_list = array( $notification->user_id );
		$push_api->send_to_users( $title, $text, $users_list, $url);
	}

	/**
	* Sent messagess using push notifications
	*
	* @param bp_private_messages_Message $message
	*/
	static public function messages_message_after_save($message) {
		$push_api = self::get_push_api();

		$title = $message->subject;
		$text = wp_trim_words($message->message, 20, '...' );

		$sender_name = bp_core_get_user_displayname($message->sender_id);
		$url = bp_displayed_user_domain() . bp_get_messages_slug() . '/view/' . $message->thread_id . '/';

		$users_list = array();
		foreach ($message->recipients as $user) {
			$users_list[] = $user->user_id;
		}

		// Use predefined or translated string
		$default_title = "New message from $sender_name";
		$title = CanvasBp::get_bp_string(array( 'messages-unread', 'post_title' ), array( '[{{{site.name}}}]' => '', '{{sender.name}}' => $sender_name), $default_title);

		self::normalize_title_and_text($title, $text);
		$push_api->send_to_users(  $default_title . ': ' . $title, $text, $users_list, $url);
	}

	/**
	* Sent message notices using push notifications
	*
	* @param bp_private_messages_Notice $message
	*/
	static public function messages_notice_after_save($message) {
		$push_api = self::get_push_api();

		$title = $message->subject;
		$text = $message->message;
		$url = self::extract_url($text);

		self::normalize_title_and_text($title, $text);
		$push_api->send_to_users( 'BP Notice: ' . $title, $text, true, $url);
	}

	/**
	* Get full-text description for a notification.
	*
	* @param BP_Notifications_Notification $notification
	* @return string
	*/
	static private function notification_description( $notification ) {
		// use the same way as native function bp_get_the_notification_description()
		$bp = buddypress();
		$description = '';

		// Callback function exists.
		if ( isset( $bp->{ $notification->component_name }->notification_callback ) && is_callable( $bp->{ $notification->component_name }->notification_callback ) ) {
			$description = call_user_func( $bp->{ $notification->component_name }->notification_callback, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'string', $notification->id );

			// @deprecated format_notification_function - 1.5
		} elseif ( isset( $bp->{ $notification->component_name }->format_notification_function ) && function_exists( $bp->{ $notification->component_name }->format_notification_function ) ) {
			$description = call_user_func( $bp->{ $notification->component_name }->format_notification_function, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1 );

			// Allow non BuddyPress components to hook in.
		} else {
			$description = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', array( $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 'string', $notification->component_action, $notification->component_name, $notification->id ) );
		}

		return apply_filters( 'bp_get_the_notification_description', $description, $notification );
	}

	static private function extract_url(&$message) {
		$url = '';
		if (preg_match( '!^<a href="(.*?)"[^>]*?>([^<]*?)</a>$!', $message, $m )) {
			$url = $m[1];
			$message = $m[2];
		}
		return $url;
	}

	/**
	* Get title for a notification.
	*
	* @param BP_Notifications_Notification $notification
	* @return string
	*/
	static private function notification_title( $notification ) {
		$actions = explode( '_', $notification->component_action );
		foreach( $actions as $key => $val ) {
			$actions[ $key ] = ucfirst( $val );
		}
		return implode( ' ', $actions );
	}

	static private function option_on($name) {
		return Canvas::get_option($name);
	}

	/**
	* Get template string from BP and substitute values
	*
	* @param array $names
	* @param array $values
	* @param string $default
	*/
	static function get_bp_string($names, $values = array(), $default = '') {
		$result = array();
		if (class_exists( 'BuddyPress' )) {
			BuddyPress::instance(); // initialize it
		}
		if (function_exists( 'bp_email_get_schema' )) {
			$result = bp_email_get_schema();
			if (!is_array($names)) {
				$names = array($names);
			}
			foreach ($names as $name) {
				if (isset($result[$name])) {
					$result = $result[$name];
				} else {
					$result = $default;
					break;
				}
			}
		}
		if (empty($result) || !is_string($result)) {
			$result = $default;
		}
		// substitute values
		return trim(str_replace(array_keys($values), array_values($values), $result));
	}

	/**
	* Return CanvasNotifications instance
	*
	* @return CanvasNotifications
	*/
	static function get_push_api() {
		if (!class_exists( 'CanvasNotifications' )) {
			require_once(dirname(__FILE__) . '/push/canvas-notifications.class.php' );
		}
		return CanvasNotifications::get();
	}

	/**
	* Decode html entity codes
	*
	* @param string $title
	* @param string $text
	*/
	static private function normalize_title_and_text(&$title, &$text) {
		$title = html_entity_decode($title, ENT_QUOTES | ENT_HTML401);
		$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML401);
	}
}

if (Canvas::get_option( 'bp_private_messages' ) || Canvas::get_option( 'bp_global_messages' ) || Canvas::get_option( 'bp_friends' ) || Canvas::get_option( 'bp_other_notitications' )) {
	add_action( 'bp_init',  array( 'CanvasBp', 'init' ));
}
