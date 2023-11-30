<?php 
    
	/** All constants */

	define( "MH_THEME_DIR_URI", get_stylesheet_directory_uri() );
	define( "MH_THEME_DIR_PATH", get_stylesheet_directory() );
	define( "MH_THEME_DOMAIN", "mortgage-house" );
	define( "MH_THEME_VERSION", "1.0");

	/** Requires files */

	require_once( MH_THEME_DIR_PATH . "/includes/mh-dequeue.php" );
	require_once( MH_THEME_DIR_PATH . "/includes/mh-enqueue.php" );
	require_once( MH_THEME_DIR_PATH . "/includes/mh-utils.php" );
	require_once( MH_THEME_DIR_PATH . "/includes/mh-ajax-request.php" );


	/** Hide admin bar for subscribers */

	if (!current_user_can('edit_posts')) {
		add_filter('show_admin_bar', '__return_false');
	}

	/** */

	// Add this code to your theme's functions.php file or in a custom plugin


	add_filter('auth_cookie_expiration', 'custom_session_timeout');
	function custom_session_timeout($expiration) {
		return 180; // 10 seconds for testing, replace with your desired timeout
	}

	// Hook to check user activity on every page load
	add_action('init', 'check_user_activity');
	function check_user_activity() {
		// Check if the user is logged in
		if (is_user_logged_in()) {
			// Update user meta with the current timestamp
			update_user_meta(get_current_user_id(), 'last_activity', time());
		}
	}

	// Hook to perform actions after user logout
	add_action('wp_logout', 'custom_logout_action');
	function custom_logout_action() {
		// Perform your custom actions here after logout

		// Redirect to the home page
		wp_redirect(home_url());
		exit();
	}

	// Hook to check for inactive users and perform actions
	add_action('wp', 'check_inactive_users');
	function check_inactive_users() {
		// Check if the user is logged in
		if (is_user_logged_in()) {
			$last_activity = get_user_meta(get_current_user_id(), 'last_activity', true);

			// Check if the user has been inactive for more than the specified timeout
			if ($last_activity && (time() - $last_activity) > 180 ) {
		
				wp_logout();

				// Redirect to home page
				wp_redirect(home_url());
				exit();
			}
		}
	}



/** Register user create end point */

if( ! function_exists("initialize_rest_api") ) {
		
	function initialize_rest_api() {
		register_rest_route('mortgage/v2', '/register', array(
			'methods'  => 'POST',
			'callback' => 'mh_handle_signup',
			'permission_callback' => '__return_true', 
		));
	}

	add_action('rest_api_init', 'initialize_rest_api');
}



