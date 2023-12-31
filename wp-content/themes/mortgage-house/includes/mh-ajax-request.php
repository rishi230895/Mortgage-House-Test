<?php
    require_once __DIR__ . '/vendor/autoload.php';
    use PHPGangsta\GoogleAuthenticator\GoogleAuthenticator;

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
                $response['fields_error'] = array();
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
            }if (!email_exists($email)){
                $is_error = true;
                $error_lists['email'] = __("Email Address doesn't exists.", MH_THEME_DOMAIN );
            }



    
            if( ! $is_error  ) {

                /** If 2Fa is on  */

                $user_id = '';
                $is_two_fa_on = '';

                
                $userinfo = get_user_by('email', $email);

                if( $userinfo  ) {
                    $user_id = $userinfo ->ID;
                    $is_two_fa_on = get_user_meta($user_id, 'switch_two_factor_auth', true );
                }


                if( $is_two_fa_on && $is_two_fa_on == "on" ) {

                    $user = wp_authenticate_username_password(null,  $email, $password);

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

                        /** User Authenticated  */                    

                        session_start();

                        $ga = new PHPGangsta_GoogleAuthenticator();
                        $secret = $ga->createSecret();
                        $qrCodeUrl = $ga->getQRCodeGoogleUrl('Mortgage House', $secret );
                        $onecode = $ga->getCode($secret);

                        $_SESSION["secret"] = $secret;
                        $_SESSION["qrcode"] = $qrCodeUrl;
                        $_SESSION["onecode"] = $onecode;
                        $_SESSION['useremail'] = $email;
                        $_SESSION['password'] = $password;

                        $response['data'] = $qrCodeUrl;
                        $response['success'] = true;
                        $response['message'] = __('Authenticated success.', MH_THEME_DOMAIN );
                        $response['error'] = false;
                        $response['nonce_error'] = false;
                        $response['success'] = true;
                        $response['fields_error'] = $error_lists;
                        $response['redirect'] = get_author_posts_url( $user->ID );
                    }
                }
                else {

                    $creds = array(
                        'user_login'    => $email,
                        'user_password' => $password,
                        'remember'      => true,
                    );

                    $user = wp_signon( $creds, false );

                    if ( is_wp_error( $user )) {

                        $response['data'] = null;
                        $response['success'] = false;
                        $response['message'] = 'User created ,'. $user->get_error_message();
                        $response['error'] = true;
                        $response['nonce_error'] = false;
                        $response['fields_error'] = $error_lists;
                        $response['redirect'] = null;

                    } 
                    else {

                        $response['data'] = null;
                        $response['success'] = true;
                        $response['message'] = "Login Success";
                        $response['error'] = false;
                        $response['nonce_error'] = false;
                        $response['fields_error'] = $error_lists;
                        $response['redirect'] = get_author_posts_url( $user_id );
                    }
                    
                }

            }else{
                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = "";
                $response['error'] = false;
                $response['nonce_error'] = false;
                $response['fields_error'] = $error_lists;
                $response['redirect'] = "";
            }
        
            echo json_encode( $response );
            exit;
        }

        add_action('wp_ajax_sign_in_action', 'mh_handle_sign_in');
        add_action('wp_ajax_nopriv_sign_in_action', 'mh_handle_sign_in');
    }


    /** Register User  */

    

if( ! function_exists('mh_handle_signup') ) {

	function mh_handle_signup( WP_REST_Request $request ) {

        $response = array();
		$fields_error = array();
		$is_error = false;

		/** Verify nonce  */
        $nonce = $request->get_param('nonce');
    

        if ( ! isset($nonce) || ! wp_verify_nonce( $nonce, 'mh-nonce' ) ) {

            $response['data'] = null;
            $response['success'] = false;
            $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN );
            $response['error'] = true;
            $response['nonce_error'] = true;
            $response['success'] = false;
            $response['fields_error'] = $fields_error;
            $response['redirect'] = null;
    
            return rest_ensure_response( $response );
            
        }

	
		$company_name = trim( sanitize_text_field( $request->get_param('company_name')) );
		$primary_contact_name = trim ( sanitize_text_field( $request->get_param('primary_contact_name') ) );
		$mobile_number = trim(  sanitize_text_field( $request->get_param('mobile_number') ) );
		$email_address = trim(  $request->get_param('email_address') );
		$password = trim( $request->get_param('password') );
		$address = strip_tags ( $request->get_param('address') );

		$passport_file =  $_FILES['passport_file'];
		$passport_number = trim( sanitize_text_field(  $request->get_param('passport_number') ) );
		$passport_exp_date = trim( sanitize_text_field ( $request->get_param('passport_exp_date') ) );

		$drl_file =   $_FILES['drl_file'];
		$drl_number = trim( sanitize_text_field ( $request->get_param('drl_number')  ) );
		$drl_exp_date  = trim( sanitize_text_field ( $request->get_param('drl_expt_date')  ) );


		/** Validate company name */

		if(  empty( $company_name ) ) {
			$is_error = true;
			$fields_error['companyName'] = __( "Company name should not be empty.", MH_THEME_DOMAIN );  
		}
		else {
			if( mh_validate_special_chars( $company_name ) ) {
				$is_error = true;
				$fields_error['companyName'] = __( "Special chars are not allowed.", MH_THEME_DOMAIN );  
			}
		}

	  
		/** Validate Primary Contact Name */

		if(  empty( $primary_contact_name ) ) {
			$is_error = true;
			$fields_error['primaryContactName'] = __( "Primary contact name should not be empty.", MH_THEME_DOMAIN );  
		}
		else {
			if( mh_validate_special_chars( $primary_contact_name ) ) {
				$is_error = true;
				$fields_error['primaryContactName'] = __( "Special chars are not allowed.", MH_THEME_DOMAIN );  
			}
		}


		/** Validate Mobile Contact Name */

		if(  empty( $mobile_number ) ) {
			$is_error = true;
			$fields_error['mobileNumber'] = __( "Mobile number should not be empty.", MH_THEME_DOMAIN );  
		}
		else {
			if( ! mh_validate_aus_mobile_numb( $mobile_number )  ) {
				$is_error = true;
				$fields_error['mobileNumber'] = __( "Invalid mobile number format.", MH_THEME_DOMAIN ); 
			}
		}

	
		/** Validate Email Address */

		if (empty($email_address)) {

			$is_error = true;
			$fields_error['emailAddress'] = __("Email should not be empty.", MH_THEME_DOMAIN);
		}
		 else {
			if (!mh_validate_email_address($email_address)) {
				$is_error = true;
				$fields_error['emailAddress'] = __("Invalid email format.", MH_THEME_DOMAIN);
			}
		} 

		/** Validate password */ 

		if( empty( $password )  ) {
			$is_error = true;
			$fields_error['password'] = __("Password should not be empty.", MH_THEME_DOMAIN );
		}
		else {
			if ( ! mh_validate_password( $password ) ) {
				$is_error = true;
				$fields_error['password'] = __("Password length 12-16 chars and password should contain atleast one uppercase, lowercase, number and special character. ", MH_THEME_DOMAIN);
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
			$fields_error['passportFile'] = __("File Passport should not be empty.", MH_THEME_DOMAIN );
		}
		else {

			if( ! mh_validate_file_format( $passport_file ) ) {
				$is_error = true;
				$fields_error['passportFile'] = __("Invalid file format, supporting formats (png,jpeg,jpg,pdf).", MH_THEME_DOMAIN );
			}
			else {
				if(  mh_validate_file_max_size( $passport_file , 10 ) ) {
					$is_error = true;
					$fields_error['passportFile'] = __("File max size should not not greater than 10", MH_THEME_DOMAIN );
				}
			}
		}

		/** Validate the passport number  */

		if( empty( $passport_number )) {
			$is_error = true;
			$fields_error['passportNumber'] = __("Passport number should not be empty.", MH_THEME_DOMAIN );
		}
		else {
			if( ! mh_validate_passport_number( $passport_number) ) {
				$is_error = true;
				$fields_error['passportNumber'] = __("Invalid passport number.", MH_THEME_DOMAIN );
			}
		}
	  
		/** Validate expiry date of passport  */

		if ( empty ( $passport_exp_date  )  ) {
			$is_error = true;
			$fields_error['passportExpDate'] = __("Expiry Date should not be empty.", MH_THEME_DOMAIN );
		}
		else {
			if( ! mh_validate_past_date($passport_exp_date) ) {
				$is_error = true;
				$fields_error['passportExpDate'] = __("Invalid expiry date.", MH_THEME_DOMAIN );
			}
		}

		/** Validate Driving license */

		if( empty ( $drl_file ) ) {
			$is_error = true;
			$fields_error['drlFile'] = __("File Driving Licence should not be empty.", MH_THEME_DOMAIN );
		}
		else {

			if( ! mh_validate_file_format( $drl_file ) ) {
				$is_error = true;
				$fields_error['drlFile'] = __("Invalid file format, supporting formats (png,jpeg,jpg,pdf).", MH_THEME_DOMAIN );
			}
			else {
				if(  mh_validate_file_max_size( $drl_file , 10 ) ) {
					$is_error = true;
					$fields_error['drlFile'] = __("File max size should not not greater than 10", MH_THEME_DOMAIN );
				}
			}
		}

		/** Validate Driving license number  */

		if ( empty ( $drl_number )  ) {
			$is_error = true;
			$fields_error['drlNumber'] = __("Driving license number should not be empty.", MH_THEME_DOMAIN );
		}
		else {
			if ( ! mh_validate_drl( $drl_number ) ) {
				$is_error = true;
				$fields_error['drlNumber'] = __("Invalid driving license number.", MH_THEME_DOMAIN );
			}
		}

		/** Validate Driving license expiry date  */

		if ( empty ( $drl_exp_date  )  ) {
			$is_error = true;
			$fields_error['drlExpDate'] = __("Expiry date should not be empty.", MH_THEME_DOMAIN );
		}
		else {
			if( ! mh_validate_past_date($drl_exp_date) ) {
				$is_error = true;
				$fields_error['drlExpDate'] = __("Invalid expiry date.", MH_THEME_DOMAIN );
			}
		}

		if (email_exists($email_address)){
			$is_error = true;
			$fields_error['emailAddress'] = __("Email Address already exists.", MH_THEME_DOMAIN );
		}

       
		/** Errors */

        if(  ! $is_error  ) {

            $user_data = array(
                'user_login' => $email_address,  
                'user_pass'  => $password, 
                'user_email' => $email_address, 
                'role'       => 'subscriber', 
            );
            
            // Create the user


            $user_id = wp_insert_user($user_data);
    
            if ( ! is_wp_error($user_id) ) {
                
                $response['data'] = null;
                $response['success'] = true;
                $response['message'] = "User created successfully.";
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
                add_user_meta( $user_id ,'switch_two_factor_auth', "on" );

                $creds = array(
                    'user_login'    => $email_address,
                    'user_password' => $password,
                    'remember'      => true,
                );

                $user = wp_signon( $creds, false );

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

		
		
		return rest_ensure_response( $response );
		
	}

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
    

/** Verify OTP  */

if( ! function_exists("mh_verify_otp")  )  {

    function mh_verify_otp() {

        $response = array();
        $fields_error = array();
        $is_error = false;

        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
       // echo $secret;

        /** Verify nonce  */
    
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mh-nonce')) {
    
            $response['data'] = null;
            $response['success'] = false;
            $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN);
            $response['error'] = true;
            $response['nonce_error'] = true;
            $response['fields_error'] = $fields_error;
            $response['redirect'] = null;
    
            echo json_encode($response);
            exit;
        }
    
        session_start();
        
        $otp_text = trim($_POST["otpText"]);
        $onecode = $_SESSION["onecode"];
        $secret = $_SESSION["secret"];

        try {
         
            $checkResult = $ga->verifyCode( $secret, $otp_text , 2);
          //  var_dump( $checkResult);
    
            if (   $checkResult  ) {
               
                $creds = array(
                    'user_login'    => $_SESSION['useremail'],
                    'user_password' => $_SESSION['password'],
                    'remember'      => true,
                );
        
                $user = wp_signon($creds, false);
        
                if ( is_wp_error($user) ) {
        
                    $response['data'] = null;
                    $response['success'] = false;
                    $response['message'] = $user->get_error_message();
                    $response['error'] = true;
                    $response['nonce_error'] = false;
                    $response['fields_error'] = $fields_error;
                    $response['redirect'] = null;
    
                } 
                else {
        
                    $response['data'] = null;
                    $response['success'] = true;
                    $response['message'] = "Verified.";
                    $response['error'] = false;
                    $response['nonce_error'] = false;
                    $response['fields_error'] = $fields_error;
                    $response['redirect'] = get_author_posts_url($user->ID);
                }
            } 
            else {
        
                $response['data'] = null;
                $response['success'] = false;
                $response['message'] = __("Passcode not match.", MH_THEME_DOMAIN);
                $response['error'] = true;
                $response['nonce_error'] = true;
                $response['fields_error'] = $fields_error;
                $response['redirect'] = null;
            }
           
            status_header(200);
    
            unset($_SESSION["secret"]);
            unset($_SESSION["qrcode"]);
            unset($_SESSION["onecode"]);
            unset($_SESSION['useremail']);
            unset($_SESSION['password']);
          
    
            echo json_encode($response);
            exit;
        }
        catch(\Exception $e) {
            echo $e->getMessage();
        }

    }
    
    add_action('wp_ajax_verify_otp_two_fa', 'mh_verify_otp');
    add_action('wp_ajax_nopriv_verify_otp_two_fa', 'mh_verify_otp');

}

/** update two fa meta key  */

if( ! function_exists('mh_update_2fa_key') ) {

    function mh_update_2fa_key () {

        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mh-nonce')) {
    
            $response['data'] = null;
            $response['success'] = false;
            $response['message'] = __("Invalid Request.", MH_THEME_DOMAIN);
            $response['error'] = true;
            $response['nonce_error'] = true;
            $response['fields_error'] = $fields_error;
            $response['redirect'] = null;
    
            echo json_encode($response);
            exit;
        }

        $toggle = trim($_POST['data']);

        if( get_user_meta(get_current_user_id(), 'switch_two_factor_auth', true) ) {
            update_user_meta( get_current_user_id(), 'switch_two_factor_auth', $toggle );
        }
        else {
            add_user_meta( get_current_user_id() , 'switch_two_factor_auth', $toggle );
        }

        echo json_encode('success');
        exit;
    
    }

    add_action('wp_ajax_update_2fa_key', 'mh_update_2fa_key');
    add_action('wp_ajax_nopriv_update_2fa_key', 'mh_update_2fa_key');
}


