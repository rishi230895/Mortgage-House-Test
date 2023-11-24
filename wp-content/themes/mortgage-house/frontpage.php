<?php 
    
    //Template Name: Frontpage

    if ( is_user_logged_in() ) {
        wp_safe_redirect( get_author_posts_url( get_current_user_id() ) );
        exit;
    }

    get_header();

    get_template_part('template-parts/login-form');

    get_footer(); 
    
