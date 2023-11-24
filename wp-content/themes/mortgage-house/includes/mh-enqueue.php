<?php

    /**
     * Script is written for enqueues all externals files.
     */

    if( ! function_exists( 'mh_enqueue_externals' ) ) {

        function mh_enqueue_externals() {

            /** JS scripts */
            
            wp_enqueue_script( 'mask-script', MH_THEME_DIR_URI.'/assets/js/jquery.mask.min.js', array(), MH_THEME_VERSION , true);
            wp_enqueue_script( 'alpine-script', MH_THEME_DIR_URI.'/assets/js/mh-alpine.min.js', array(), MH_THEME_VERSION , true);
            wp_enqueue_script( 'mh-utils-script', MH_THEME_DIR_URI . '/assets/js/mh-utils.js', array( 'jquery' ), time(), true );
            wp_enqueue_script( 'mh-main-script',  MH_THEME_DIR_URI . '/assets/js/mh-main.js', array( 'mh-utils-script' ), time(), true );

            wp_localize_script('mh-main-script', 'mh_main_script_vars', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'security' => wp_create_nonce( 'mh-nonce' ),
                'theme_url' => MH_THEME_DIR_URI
            ));

            /** CSS Styles */

            wp_enqueue_style( 'prod-style',  MH_THEME_DIR_URI . '/assets/css/production-style.min.css', array(), false );
            wp_enqueue_style( 'font-awesome-solid', MH_THEME_DIR_URI . '/assets/fonts/fontawesome/css/solid.min.css' ); 
            wp_enqueue_style( 'font-awesome-brands', MH_THEME_DIR_URI . '/assets/fonts/fontawesome/css/brands.min.css' ); 
            wp_enqueue_style( 'font-awesome', MH_THEME_DIR_URI . '/assets/fonts/fontawesome/css/fontawesome.min.css' ); 

        }

        add_action( 'wp_enqueue_scripts', 'mh_enqueue_externals' );
    }
    


    