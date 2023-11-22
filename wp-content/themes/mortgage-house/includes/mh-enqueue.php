<?php

    /**
     * Script is written for enqueues all externals files.
     */

    if( ! function_exists( 'mh_enqueue_externals' ) ) {

        function mh_enqueue_externals() {

            /** JS scripts */
            
            wp_enqueue_script( 'mh-utils-script', MH_THEME_DIR_URI . '/assets/js/mh-utils.js', array( 'jquery' ), time(), true );
            wp_enqueue_script( 'mh-main-script',  MH_THEME_DIR_URI . '/assets/js/mh-main.js', array( 'mh-utils-script', 'jquery' ), time(), true );
            
            wp_localize_script('mh-main-script', 'mh_main_script_vars', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'security' => wp_create_nonce( 'mh-nonce' ),
                'theme_url' => MH_THEME_DIR_URI
            ));

            /** CSS Styles */
        }

        add_action( 'wp_enqueue_scripts', 'mh_enqueue_externals' );
    }
    

    
    