<?php

    /** Registration Ajax */

    if( ! function_exists( 'mh_register_new_user' ) ) {

        function mh_register_new_user() {

            $response = array();
            $fields_error = array();
            $is_error = false;

            /** Verify nonce  */

            if ( ! isset($_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mh-nonce') ) {

                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN );
                $response['error'] = true;
                $response['nonce_error'] = true;
                $response['success'] = false;
                $response['fields_error'] = false;

                echo json_encode( $response );
                exit;
            }

            /** Get register fields values  */

            $pm_contact_name = sanitize_text_field( trim( $_POST["primary-contact-numb"] ) ) ;
            $comp_name = sanitize_text_field( $_POST["comp-name"] );
            $mob_numb = $_POST["mob-numb"];
            $email_address = $_POST["email-address"];
            $password = $_POST["password"];
            $address = sanitize_textarea_field( trim ( $_POST["address"] ) );

            /**  Validate Primary Contact Name  */

            if( ! empty( $pm_contact_name ) ) {
                if( mh_validate_special_chars( $pm_contact_name ) ) {
                    $is_error = true;
                    $fields_error['contact_name'] = __("Special characters are not allowed.", MH_THEME_DOMAIN );
                }
            }
            else {
                $is_error = true;
                $fields_error['contact_name'] = __("Primary contact should not empty.", MH_THEME_DOMAIN );
            }

            /** Validate company name  */

            if( ! empty( $comp_name ) ) {
                if( mh_validate_special_chars( $comp_name ) ) {
                    $is_error = true;
                    $fields_error['contact_name'] = __("Special characters are not allowed.", MH_THEME_DOMAIN );
                }
            }
            else {
                $is_error = true;
                $fields_error['contact_name'] = __("Company name should not empty.", MH_THEME_DOMAIN );
            }

            /**  Validate Mobile numbers.  */

            if( ! empty( $mob_numb) ) {
                if( ! mh_validate_aus_mobile_numb( $mob_numb  ) ) {
                    $is_error = true;
                    $fields_error['mobile_number'] = __("Please enter valid format.", MH_THEME_DOMAIN );
                }
            }
            else {
                $is_error = true;
                $fields_error['mobile_number'] = __("Mobile number should not empty.", MH_THEME_DOMAIN );
            }

            /**  Validate email numbers.  */

            if( ! empty( $email_address ) ) {
                if( ! mh_validate_email_address( $email_address  ) ) {
                    $is_error = true;
                    $fields_error['email_address'] = __("Please enter valid email.", MH_THEME_DOMAIN );
                }
            }
            else {
                $is_error = true;
                $fields_error['email_address'] = __("Email should not empty.", MH_THEME_DOMAIN );
            }


            /** Validate password  */

            if( ! empty( $password )  ) {
                if ( mh_validate_password( $password ) ) {
                    $is_error = true;
                    $fields_error['password'] = __("Min length 8 chars and password should contain atleast one uppercase, lowercase, number and special character. ", MH_THEME_DOMAIN);
                }
            }
            else {
                $is_error = true;
                $fields_error['password'] = __("Password should not empty.", MH_THEME_DOMAIN );
            }


            /** Validate Address  */

            if( empty( $address ) ) {
                $is_error = true;
                $fields_error['address'] = __("Address should not empty.", MH_THEME_DOMAIN );
            }

            /** Validate File upload  */            

            if( ! empty ( $dr_license_file ) ) {

                if(  mh_validate_file_max_size( $file , 10 ) ) {
                   if( ! mh_validate_file_format( $file ) ) {
                        $is_error = true;
                        $fields_error['driving_licence'] = __("Invalid file format, supporting formats (png,jpeg,jpg,pdf).", MH_THEME_DOMAIN );
                   }
                }
                else {
                    $is_error = true;
                    $fields_error['driving_licence'] = __("File max size should not not greater than 10", MH_THEME_DOMAIN );
                }
            }
            else {
                $is_error = true;
                $fields_error['driving_licence'] = __("File Driving licence should not empty.", MH_THEME_DOMAIN );
            }

        }
    
        add_action('wp_ajax_register_new_user', 'mh_register_new_user');
        add_action('wp_ajax_nopriv_register_new_user', 'mh_register_new_user');
    }

    /** Sign In */

    if( ! function_exists('mh_handle_sign_in') ) {

        function mh_handle_sign_in() {

            $response = array();
            $is_error = false;
            $error_lists = array();

            if ( ! isset($_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mh-nonce') ) {

                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN );
                $response['error'] = true;
                $response['nonce_error'] = true;
                $response['success'] = false;
                $response['fields_error'] = false;
                $response['redirect'] = null;

                echo json_encode( $response );
                exit;
            }

            
            $email = sanitize_user( $_POST['email'] );
            $password = $_POST['password'];
            /** Validate Email  Addresss  */

            if( empty( $email  )  ) {
                $is_error = true;
                $error_lists['email'] = __('Email address should not empty.' , MH_THEME_DOMAIN );
            }
            else {
                if( ! mh_validate_email_address( $email )  ) {
                    $is_error = true;
                    $error_lists['email'] = __('Invalid email address.' , MH_THEME_DOMAIN );
                }
            }

            /** Validate Password  */

            if( empty( $password  )  ) {
                $is_error = true;
                $error_lists['password'] = __('Password should not empty' , MH_THEME_DOMAIN );
            }

    
            if( ! $is_error  ) {

                $credentials = array(
                    'user_login' => $email,
                    'user_password' => $password,
                    'remember' => true,
                );

                $user = wp_signon($credentials, false);

                if ( is_wp_error( $user ) ) {

                    $response['data'] = null;
                    $response['success'] = false;
                    $response['message'] = __( strip_tags( $user->get_error_message()  ), MH_THEME_DOMAIN );
                    $response['error'] = true;
                    $response['nonce_error'] = false;
                    $response['success'] = false;
                    $response['fields_error'] = $error_lists;
                    $response['redirect'] = null;
                    
                } 
                else {

                    $response['data'] = null;
                    $response['success'] = true;
                    $response['message'] = __('Authenticated success.', MH_THEME_DOMAIN );
                    $response['error'] = false;
                    $response['nonce_error'] = false;
                    $response['success'] = true;
                    $response['fields_error'] = $error_lists;
                    $response['redirect'] = get_author_posts_url( $user->ID );
            
                }
            }
           
            echo json_encode( $response );
            exit;
        }

        add_action('wp_ajax_sign_in_action', 'mh_handle_sign_in');
        add_action('wp_ajax_nopriv_sign_in_action', 'mh_handle_sign_in');
    }


    /** Register User  */

    if( ! function_exists('mh_handle_signup') ) {

        function mh_handle_signup() {

            $response = array();
            $fields_error = array();
            $is_error = false;

            /** Verify nonce  */

            if ( ! isset($_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mh-nonce') ) {

                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN );
                $response['error'] = true;
                $response['nonce_error'] = true;
                $response['success'] = false;
                $response['fields_error'] = false;

                echo json_encode( $response );
                exit;
            }


            $company_name = trim( sanatize_text_field( $_POST['company'] ) );
            $primary_contact_name = trim ( sanatize_text_field( $_POST['primary_contact_name'] ) );
            $mobile_number = trim(  sanatize_text_field( $_POST['mobile_number'] ) );
            $email_address = trim($_POST['email_address']);
            $password = $_POST['password'];
            $address = strip_tags ( trim($_POST['address']) );

            $passport_file = $_FILES['passport_file'];
            $passport_number = trim( sanatize_text_field(  $_POST['passport_number'] ) );
            $passport_exp_date = trim( sanatize_text_field ( $_POST['passport_exp_date'] ) );

            $drl_file = $_FILES['drl_file'];
            $drl_number = trim( sanatize_text_field ( $_POST['drl_number'] ) );
            $drl_exp_date  = trim( sanatize_text_field ( $_POST['drl_expt_date'] ) );

        
            /** Validate company name */

            if(  empty( $company_name ) ) {
                $is_error = true;
                $fields_error['company_name'] = __( "Company name should not empty.", MH_THEME_DOMAIN );  
            }
            else {
                if( mh_validate_special_chars( $company_name ) ) {
                    $is_error = true;
                    $fields_error['company_name'] = __( "Special chars not allowed.", MH_THEME_DOMAIN );  
                }
            }


            /** Validate Primary Contact Name */

            if(  empty( $primary_contact_name ) ) {
                $is_error = true;
                $fields_error['primary_contact_name'] = __( "Contact name should not empty.", MH_THEME_DOMAIN );  
            }
            else {
                if( mh_validate_special_chars( $primary_contact_name ) ) {
                    $is_error = true;
                    $fields_error['primary_contact_name'] = __( "Special chars not allowed.", MH_THEME_DOMAIN );  
                }
            }


            /** Validate Primary Contact Name */

            if(  empty( $mobile_number ) ) {
                $is_error = true;
                $fields_error['mobile_number'] = __( "Mobile number should not empty.", MH_THEME_DOMAIN );  
            }
            else {
                if( ! mh_validate_aus_mobile_numb( $mobile_number )  ) {
                    $is_error = true;
                    $fields_error['mobile_number'] = __( "Invalid mobile number format.", MH_THEME_DOMAIN ); 
                }
            }

            /** Validate Email Address */

            if( ! empty( $email_address ) ) {
                $is_error = true;
                $fields_error['email_address'] = __("Email should not empty.", MH_THEME_DOMAIN );
            }
            else {
                if( ! mh_validate_email_address( $email_address  ) ) {
                    $is_error = true;
                    $fields_error['email_address'] = __("Email should not empty.", MH_THEME_DOMAIN );
                }
            }

            /** Validate password */ 

            if( empty( $password )  ) {
                $is_error = true;
                $fields_error['password'] = __("Password should not empty.", MH_THEME_DOMAIN );
            }
            else {
                if ( ! mh_validate_password( $password ) ) {
                    $is_error = true;
                    $fields_error['password'] = __("Password length 8-12 chars and password should contain atleast one uppercase, lowercase, number and special character. ", MH_THEME_DOMAIN);
                }
            }


            /** Validate Address  */

            if( empty( $address ) ) {
                $is_error = true;
                $fields_error['address'] = __("Address should not empty.", MH_THEME_DOMAIN );
            }


            /** Validate Passport file  */

            if( empty ( $passport_file ) ) {

                $is_error = true;
                $fields_error['passport_file'] = __("File Driving licence should not empty.", MH_THEME_DOMAIN );

            }
            else {

                if(  mh_validate_file_max_size( $passport_file , 10 ) ) {

                    $is_error = true;
                    $fields_error['passport_file'] = __("File max size should not not greater than 10", MH_THEME_DOMAIN );

                }
                else {
                    if( ! mh_validate_file_format( $passport_file ) ) {
                        $is_error = true;
                        $fields_error['passport_file'] = __("Invalid file format, supporting formats (png,jpeg,jpg,pdf).", MH_THEME_DOMAIN );
                    }
                }

            }

            /** Validate mobile  */

            echo json_encode( $fields_error );
            exit;
            
        }

        add_action('wp_ajax_sign_up_action', 'mh_handle_signup');
        add_action('wp_ajax_nopriv_sign_up_action', 'mh_handle_signup');
    }


