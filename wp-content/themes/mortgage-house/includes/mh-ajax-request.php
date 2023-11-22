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
            $address = sanitize_textarea_field( trim ($_POST["address"]) );

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


            /** Address File upload  */

            if( empty( $address ) ) {
                $is_error = true;
                $fields_error['address'] = __("Address should not empty.", MH_THEME_DOMAIN );
            }

            /** File upload  */            

            if( ! empty ( $dr_license_file ) ) {
                if( mh_validate_dr_license_file() ) {
                    
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