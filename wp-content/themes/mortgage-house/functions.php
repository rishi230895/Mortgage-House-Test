<?php 

	/** All constants */

	define( "MH_THEME_DIR_URI", get_stylesheet_directory_uri() );
	define( "MH_THEME_DIR_PATH", get_stylesheet_directory() );
	define( "MH_THEME_DOMAIN", "mortgage-house" );

	/** Requires files */

	require_once( MH_THEME_DIR_PATH . "/includes/mh-enqueue.php" );
	require_once( MH_THEME_DIR_PATH . "/includes/mh-utils.php" );
	require_once( MH_THEME_DIR_PATH . "/includes/mh-ajax-request.php" );

	
