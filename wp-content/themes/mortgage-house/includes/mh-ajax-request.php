<?php

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
                $response['fields_error'] = $fields_error;
                $response['redirect'] = null;

                echo json_encode( $response );
                exit;
            }

           

            $company_name = trim( sanitize_text_field( $_POST['company_name'] ) );
            $primary_contact_name = trim ( sanitize_text_field( $_POST['primary_contact_name'] ) );
            $mobile_number = trim(  sanitize_text_field( $_POST['mobile_number'] ) );
            $email_address = trim($_POST['email_address']);
            $password = $_POST['password'];
            $address = strip_tags ( trim($_POST['address']) );

            $passport_file =  $_FILES['passport_file'];
            $passport_number = trim( sanitize_text_field(  $_POST['passport_number'] ) );
            $passport_exp_date = trim( sanitize_text_field ( $_POST['passport_exp_date'] ) );

            $drl_file =   $_FILES['drl_file'];
            $drl_number = trim( sanitize_text_field ( $_POST['drl_number'] ) );
            $drl_exp_date  = trim( sanitize_text_field ( $_POST['drl_expt_date'] ) );


            /** Validate company name */

            if(  empty( $company_name ) ) {
                $is_error = true;
                $fields_error['company_name'] = __( "Company name should not be empty.", MH_THEME_DOMAIN );  
            }
            else {
                if( mh_validate_special_chars( $company_name ) ) {
                    $is_error = true;
                    $fields_error['company_name'] = __( "Special chars are not allowed.", MH_THEME_DOMAIN );  
                }
            }

          
            /** Validate Primary Contact Name */

            if(  empty( $primary_contact_name ) ) {
                $is_error = true;
                $fields_error['primary_contact_name'] = __( "Primary contact name should not be empty.", MH_THEME_DOMAIN );  
            }
            else {
                if( mh_validate_special_chars( $primary_contact_name ) ) {
                    $is_error = true;
                    $fields_error['primary_contact_name'] = __( "Special chars are not allowed.", MH_THEME_DOMAIN );  
                }
            }


            /** Validate Mobile Contact Name */

            if(  empty( $mobile_number ) ) {
                $is_error = true;
                $fields_error['mobile_number'] = __( "Mobile number should not be empty.", MH_THEME_DOMAIN );  
            }
            else {
                if( ! mh_validate_aus_mobile_numb( $mobile_number )  ) {
                    $is_error = true;
                    $fields_error['mobile_number'] = __( "Invalid mobile number format.", MH_THEME_DOMAIN ); 
                }
            }

        
            /** Validate Email Address */

            if (empty($email_address)) {

                $is_error = true;
                $fields_error['email_address'] = __("Email should not be empty.", MH_THEME_DOMAIN);
            }
             else {
                if (!mh_validate_email_address($email_address)) {
                    $is_error = true;
                    $fields_error['email_address'] = __("Invalid email format.", MH_THEME_DOMAIN);
                }
            } 

            /** Validate password */ 

            if( empty( $password )  ) {
                $is_error = true;
                $fields_error['password'] = __("Password should not bes empty.", MH_THEME_DOMAIN );
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
                $fields_error['address'] = __("Address should not be empty.", MH_THEME_DOMAIN );
            }


            /** Validate Passport file  */

            if( empty ( $passport_file ) ) {
                $is_error = true;
                $fields_error['passport_file'] = __("File Passport should not be empty.", MH_THEME_DOMAIN );
            }
            else {

                if( ! mh_validate_file_format( $passport_file ) ) {
                    $is_error = true;
                    $fields_error['passport_file'] = __("Invalid file format, supporting formats (png,jpeg,jpg,pdf).", MH_THEME_DOMAIN );
                }
                else {
                    if(  mh_validate_file_max_size( $passport_file , 10 ) ) {
                        $is_error = true;
                        $fields_error['passport_file'] = __("File max size should not not greater than 10", MH_THEME_DOMAIN );
                    }
                }
            }

            /** Validate the passport number  */

            if( empty( $passport_number )) {
                $is_error = true;
                $fields_error['passport_number'] = __("Passport number should not be empty.", MH_THEME_DOMAIN );
            }
            else {
                if( ! mh_validate_passport_number( $passport_number) ) {
                    $is_error = true;
                    $fields_error['passport_number'] = __("Invalid passport number.", MH_THEME_DOMAIN );
                }
            }
          
            /** Validate expiry date of passport  */

            if ( empty ( $passport_exp_date  )  ) {
                $is_error = true;
                $fields_error['passport_exp_date'] = __("Expiry Date should not be empty.", MH_THEME_DOMAIN );
            }
            else {
                if( ! mh_validate_past_date($passport_exp_date) ) {
                    $is_error = true;
                    $fields_error['passport_exp_date'] = __("Invalid expiry date.", MH_THEME_DOMAIN );
                }
            }

            /** Validate Driving license */

            if( empty ( $drl_file ) ) {
                $is_error = true;
                $fields_error['dr_file'] = __("File Driving Licence should not be empty.", MH_THEME_DOMAIN );
            }
            else {

                if( ! mh_validate_file_format( $drl_file ) ) {
                    $is_error = true;
                    $fields_error['dr_file'] = __("Invalid file format, supporting formats (png,jpeg,jpg,pdf).", MH_THEME_DOMAIN );
                }
                else {
                    if(  mh_validate_file_max_size( $drl_file , 10 ) ) {
                        $is_error = true;
                        $fields_error['dr_file'] = __("File max size should not not greater than 10", MH_THEME_DOMAIN );
                    }
                }
            }

            /** Validate Driving license number  */

            if ( empty ( $drl_number )  ) {
                $is_error = true;
                $fields_error['dr_license_number'] = __("Driving license number should not be empty.", MH_THEME_DOMAIN );
            }
            else {
                if ( ! mh_validate_drl( $drl_number ) ) {
                    $is_error = true;
                    $fields_error['dr_license_number'] = __("Invalid driving license number.", MH_THEME_DOMAIN );
                }
            }

            /** Validate Driving license expiry date  */

            if ( empty ( $drl_exp_date  )  ) {
                $is_error = true;
                $fields_error['dr_exp_date'] = __("Expiry date should not be empty.", MH_THEME_DOMAIN );
            }
            else {
                if( ! mh_validate_past_date($drl_exp_date) ) {
                    $is_error = true;
                    $fields_error['dr_exp_date'] = __("Invalid expiry date.", MH_THEME_DOMAIN );
                }
            }

            /** Errors */

            if(  ! $is_error  ) {

                $user_data = array(
                    'user_login' => $email_address,  
                    'user_pass'  => $password,  // Replace with the desired password
                    'user_email' => $email_address,  // Replace with the desired email address
                    'role'       => 'subscriber',  // Set the user role to 'subscriber'
                );
                
                // Create the user
                $user_id = wp_insert_user($user_data);
        
                if ( ! is_wp_error($user_id) ) {
                    
                    $response['data'] = null;
                    $response['success'] = true;
                    $response['message'] = "User created successfully";
                    $response['error'] = false;
                    $response['nonce_error'] = false;
                    $response['fields_error'] = $fields_error;
                    $response['redirect'] = get_author_posts_url( $user_id );

                    /** Save Company info */

                    $passport_attachment_id = mh_file_upload_in_media( $passport_file );
                    $drl_attachment_id= mh_file_upload_in_media( $drl_file );

                    $company_name = trim( sanitize_text_field( $_POST['company_name'] ) );
                    $primary_contact_name = trim ( sanitize_text_field( $_POST['primary_contact_name'] ) );
                    $mobile_number = trim(  sanitize_text_field( $_POST['mobile_number'] ) );
                    $email_address = trim($_POST['email_address']);
                    $password = $_POST['password'];
                    $address = strip_tags ( trim($_POST['address']) );
        
    
                    add_user_meta( $user_id ,'company_name', $company_name);
                    add_user_meta( $user_id ,'primary_contact_name', $primary_contact_name );
                    add_user_meta( $user_id ,'mobile_number', $mobile_number );
                    add_user_meta( $user_id ,'email_address', $email_address);
                    add_user_meta( $user_id ,'address', $address);
                    
                    add_user_meta( $user_id ,'passport_file_attach_id', $passport_attachment_id);
                    add_user_meta( $user_id ,'passport_number', $passport_number );
                    add_user_meta( $user_id ,'passport_exp_date', $passport_exp_date );
                    
                    add_user_meta( $user_id ,'drl_file_attach_id', $drl_attachment_id);
                    add_user_meta( $user_id ,'drl_number', $drl_number );
                    add_user_meta( $user_id ,'drl_exp_date', $drl_exp_date );

                    add_user_meta( $user_id ,'switch_two_factor_auth', "off" );

                    $creds = array(
                        'user_login'    => $email_address, // Assuming email is used for login
                        'user_password' => $password,
                        'remember'      => true,
                    );

                    $user = wp_signon($creds, false);

                    if ( is_wp_error( $user )) {

                        $response['data'] = null;
                        $response['success'] = false;
                        $response['message'] = 'User created ,'. $user->get_error_message();
                        $response['error'] = true;
                        $response['nonce_error'] = false;
                        $response['fields_error'] = $fields_error;
                        $response['redirect'] = null;

                    } 
                    else {
                        $response['data'] = null;
                        $response['success'] = true;
                        $response['message'] = "User created.";
                        $response['error'] = false;
                        $response['nonce_error'] = false;
                        $response['fields_error'] = $fields_error;
                        $response['redirect'] = get_author_posts_url( $user_id );
                    }

                } 
                else {
                    
                    $response['data'] = null;
                    $response['success'] = false;
                    $response['message'] = $user_id->get_error_message();
                    $response['error'] = true;
                    $response['nonce_error'] = false;
                    $response['fields_error'] = $fields_error;
                    $response['redirect'] = null;
                }

            }
            else {
                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = "";
                $response['error'] = true;
                $response['nonce_error'] = false;
                $response['fields_error'] = $fields_error;
                $response['redirect'] = null;
            }
            
            echo json_encode( $response );
            exit;

        }

        add_action('wp_ajax_sign_up_action', 'mh_handle_signup');
        add_action('wp_ajax_nopriv_sign_up_action', 'mh_handle_signup');
    }

    /** Update User Profile */
    
    if( ! function_exists('mh_handle_update_user_profile') ) {

        function mh_handle_update_user_profile() {
    
            $fields_error = array();
            $is_error = false;
            $current_user_id = get_current_user_id();
            $is_password_update = false;
    
            if ( ! isset($_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mh-nonce') ) {
    
                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN );
                $response['error'] = true;
                $response['nonce_error'] = true;
                $response['success'] = false;
                $response['fields_error'] = $fields_error;
                $response['redirect'] = null;
    
                echo json_encode( $response );
                exit;
            }
    
            $company_name = trim( sanitize_text_field( $_POST['company_name'] ) );
            $primary_contact_name = trim ( sanitize_text_field( $_POST['primary_contact_name'] ) );
            $mobile_number = trim( sanitize_text_field( $_POST['mobile_number'] ) );
            $password = $_POST['password'];
            $address = strip_tags ( trim($_POST['address']) );
    
            /** Validate company name */
            if( empty( $company_name ) ) {
                $is_error = true;
                $fields_error['company_name'] = __( "Company name should not be empty.", MH_THEME_DOMAIN );
            } else {
                if( mh_validate_special_chars( $company_name ) ) {
                    $is_error = true;
                    $fields_error['company_name'] = __( "Special chars are not allowed.", MH_THEME_DOMAIN );
                }
            }
    
            /** Validate Primary Contact Name */
            if( empty( $primary_contact_name ) ) {
                $is_error = true;
                $fields_error['primary_contact_name'] = __( "Primary contact name should not be empty.", MH_THEME_DOMAIN );
            } else {
                if( mh_validate_special_chars( $primary_contact_name ) ) {
                    $is_error = true;
                    $fields_error['primary_contact_name'] = __( "Special chars are not allowed.", MH_THEME_DOMAIN );
                }
            }
    
            /** Validate Mobile Contact Name */
            if( empty( $mobile_number ) ) {
                $is_error = true;
                $fields_error['mobile_number'] = __( "Mobile number should not be empty.", MH_THEME_DOMAIN );
            } else {
                if( ! mh_validate_aus_mobile_numb( $mobile_number )  ) {
                    $is_error = true;
                    $fields_error['mobile_number'] = __( "Invalid mobile number format.", MH_THEME_DOMAIN );
                }
            }
    
            /** Validate Mobile */
            if( empty( $address ) ) {
                $is_error = true;
                $fields_error['address'] = __("Address should not be empty.", MH_THEME_DOMAIN );
            }
    
            /** Validate Password  */
            if( ! empty( $password ) ) {
                if ( ! mh_validate_password( $password ) ) {
                    $is_error = true;
                    $fields_error['password'] = __("Password length 8-12 chars and password should contain at least one uppercase, lowercase, number, and special character. ", MH_THEME_DOMAIN);
                }
            }
           
    
            if( $is_error ) {
                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = "";
                $response['error'] = false;
                $response['nonce_error'] = false;
                $response['fields_error'] = $fields_error;
                $response['redirect'] = null;
                
            } else {
    
                /** Update company  */
                if( get_user_meta( $current_user_id, "company_name", true ) ) {
                    update_user_meta( $current_user_id, "company_name", $company_name );
                } else {
                    add_user_meta( $current_user_id, "company_name", $company_name );
                }
    
                /** Update primary contact number */
                if( get_user_meta( $current_user_id, "primary_contact_name", true ) ) {
                    update_user_meta( $current_user_id, "primary_contact_name", $primary_contact_name );
                } else {
                    add_user_meta( $current_user_id, "primary_contact_name", $primary_contact_name );
                }
    
                /** Update Mobile Number */
                if( get_user_meta( $current_user_id, "mobile_number", true ) ) {
                    update_user_meta( $current_user_id, "mobile_number", $mobile_number );
                } else {
                    add_user_meta( $current_user_id, "mobile_number", $mobile_number );
                }
    
                /** Update password */
                if( ! empty( $password ) ) {
                    wp_set_password( $password, $current_user_id);
                    $is_password_update = true;
                }
    
                /** Update address */

                if( get_user_meta( $current_user_id, "address", true ) ) {
                    update_user_meta( $current_user_id, "address", $address );
                } else {
                    add_user_meta( $current_user_id, "address", $address );
                }
    
                $company_name = get_user_meta( $current_user_id, "company_name", true );
                $primary_contact_name = get_user_meta( $current_user_id, "primary_contact_name", true );
                $mobile_number = get_user_meta( $current_user_id, "mobile_number", true );
                $email_address = get_user_meta( $current_user_id, "email_address", true );
                $address = get_user_meta( $current_user_id, "address", true );
                $logout_url = null;

               
    
                $updated_info = array(
                    'company_name' => $company_name,
                    'primary_contact_name' => $primary_contact_name,
                    'mobile_number' => $mobile_number,
                    'email_address' => $email_address,
                    'address' => $address
                );
    
                $response['data'] = $updated_info;
                $response['success'] = true;
                $response['message'] = __("Updated.", MH_THEME_DOMAIN );
                $response['error'] = false;
                $response['nonce_error'] = false;
                $response['fields_error'] = $fields_error;

            }
    
            echo json_encode($response);
            exit;
    
        }
    
        add_action('wp_ajax_update_user_info', 'mh_handle_update_user_profile');
        add_action('wp_ajax_nopriv_update_user_info', 'mh_handle_update_user_profile');
    }
    

    /** Logout User After 3 min */

    // if( ! function_exists( "mh_handle_logout_user" ) ) {

    //    function mh_handle_logout_user() {

    //     if ( ! isset($_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mh-nonce') ) {
    
    //         $response['data'] = null;
    //         $response['success'] = false;
    //         $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN );
    //         $response['error'] = true;
    //         $response['nonce_error'] = true;
    //         $response['fields_error'] = $fields_error;
    //         $response['redirect'] = null;

    //         echo json_encode( $response );
    //         exit;
    //     }
    //         wp_logout();
    //         $response['data'] = null;
    //         $response['success'] = true;
    //         $response['message'] = "";
    //         $response['error'] = false;
    //         $response['nonce_error'] = false;
    //         $response['fields_error'] = [];
    //         $response['redirect'] = home_url();

    //         echo json_encode( $response );
    //         exit;

    //    }

    //    add_action('wp_ajax_logout_session', 'mh_handle_logout_user');
    //    add_action('wp_ajax_nopriv_logout_session', 'mh_handle_logout_user');
    // }