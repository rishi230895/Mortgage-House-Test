<?php
          
    /** Dequeque all defaults externals */
	
    if ( ! function_exists("mh_dequeue_default_externals") ) {

        function mh_dequeue_default_externals(){

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

		add_action("wp_print_styles", "mh_dequeue_default_externals");
    }