<?php 
    
    function mortgage_house_enqueue_styles() {
        wp_enqueue_style( 'production-style', get_stylesheet_directory_uri() . './css/production-style.min.css' ); 
        wp_enqueue_style( 'font-awesome-solid', get_stylesheet_directory_uri() . './css/fontawesome/css/solid.min.css' ); 
        wp_enqueue_style( 'font-awesome-brands', get_stylesheet_directory_uri() . './css/fontawesome/css/brands.min.css' ); 
        wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . './css/fontawesome/css/fontawesome.min.css' ); 
        wp_enqueue_script( 'script-js', get_stylesheet_directory_uri() . './js/script.js', array(), true);
        wp_enqueue_script( 'alpine-js', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', array(), true);
    } 
    add_action( 'wp_enqueue_scripts', 'mortgage_house_enqueue_styles' );
    
    /* DEQUEUE WORDPRESS DEFAULT STYLES */
    if (!function_exists("dequeue_parent_styles")) {
        function dequeue_parent_styles(){
            wp_dequeue_style("twenty-twenty-one-style");
            wp_deregister_style("twenty-twenty-one-style");
            wp_dequeue_style("wp-block-library");
            wp_dequeue_style("classic-theme-styles");
            wp_dequeue_style("global-styles");
            wp_dequeue_style("wp-block-library");
            wp_dequeue_style("twenty-twenty-one-print-style");
            wp_dequeue_style( 'wp-block-library' );
            wp_dequeue_style( 'wp-block-library-theme' );
        }
    }
    add_action("wp_print_styles", "dequeue_parent_styles");

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

  

 
