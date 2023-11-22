<?php 
    
    function mortgage_house_enqueue_styles() {
        wp_enqueue_style( 'production-style', get_stylesheet_directory_uri() . './css/production-style.min.css' ); 
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

 ?>