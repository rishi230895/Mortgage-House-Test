
<?php

    /**
     * @param string $mobile_numb 
     * @return boolean
     * 
     * Validate mobile number in australian format.
     */

    if( ! function_exists( 'mh_validate_aus_mobile_numb' ) ) {
        function mh_validate_aus_mobile_numb( $mobile_numb ) {
            if( ! empty ( $mobile_numb ) ) {

                $number = str_replace(' ', '', $mobile_numb);
                $pattern = '/^0[2-45]\d{2}\s?\d{3}\s?\d{3}$/';

                return preg_match($pattern, $number);
            }
            return false;
        }
    }

    /**
     *  Validate input has contains specials chars or not.
     * 
     *  @param string $string
     *  @return boolean
     */

    if( ! function_exists( 'mh_validate_special_chars' )) {
        function mh_validate_special_chars( $string ) {
            if( ! empty ( $string ) ) {
                $pattern = '/[!@#$%^&*(),.?":{}|<>]/';
                return preg_match($pattern, $string);
            }
            return false;
        }
    }

     /**
      *  Validate email address.
      *
      *  @param $string
      *  @return boolean
      * 
      */


    if( ! function_exists( 'mh_validate_email_address' )) {
        function mh_validate_email_address( $email_address ) {
            if( ! empty ( $email_address ) ) {
                return filter_var( $email_address , FILTER_VALIDATE_EMAIL) !== false;
            }
            return false;
        }
    }

    /**
     * 
     *  Validate password with rules.
     * 
     *  @param string $password 
     *  @return boolean
     *  
     *   
     */


    if ( ! function_exists('mh_validate_password')) {

        function mh_validate_password( $password ) {

            $min_length = 8;
            $max_length = 12;

            if (empty($password)) {
                return false;
            }

            if (strlen($password) < $min_length || strlen($password) > $max_length) {
                return false;
            }

            if (!preg_match('/[A-Z]/', $password)) {
                return false;
            }

            if (!preg_match('/[a-z]/', $password)) {
                return false;
            }

            if (!preg_match('/[0-9]/', $password)) {
                return false;
            }

            if (!preg_match('/[\!\@\#\$\%\^\&\*\(\)\+\{\}\[\]\:\;\<\>\,\.\?\~\\\\-]/', $password)) {
                return false;
            }

            return true;
        }
    }


     /**
     *  Validate file size.
     * 
     *  @param array $file , integer $max_size 
     *  @return boolean
     * 
     */

     
    if( ! function_exists( "mh_validate_file_max_size" ) ) {
        function mh_validate_file_max_size( $file , $max_size = 10 ) {
            $max_size = absint( intval( $max_size ) );
            $max_size =  $max_size * 1024 * 1024; 

            if ( $file['size'] > $max_size ) {
                return true;
            }
            return false;
        }
    }

    /**
     *  Validate file format.
     * 
     *  @param array $file 
     *  @return boolean
     * 
     */


    if( ! function_exists( "mh_validate_file_format" ) ) {
        function mh_validate_file_format( $file ) {

            $file_formats  = array(    
                'image/jpeg',
                'image/jpg',
                'image/png',
                'application/pdf',
            );

            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($file_info, $file['tmp_name']);
            finfo_close($file_info);
        
            if ( in_array( $mime, $file_formats ) ) {
                return true; 
            } 
            else {
                return false; 
            }
        }
    }

    /**
     *  Upload the file in media folder and return attachment id
     * 
     *  @param  array $file
     *  @return integer $attachment_id 
     */

    if( ! function_exists( "mh_file_upload_in_media" ) ) {

        function mh_file_upload_in_media(  $file  ) {

            $attachment_id = '';

            if ( ! mh_validate_file_max_size( $file , 10 ) &&  mh_validate_file_format( $file  ) ) {

                $upload_dir = wp_upload_dir();
                $upload_path = $upload_dir['path'] . '/';
    
                $file_name = basename($file['name']);
                $file_path = $upload_path . $file_name;
    
                if( move_uploaded_file($file['tmp_name'], $file_path) ) {

                    $file_type = wp_check_filetype($file_name, null);
        
                    $attachment = array(
                        'post_mime_type' => $file_type['type'],
                        'post_title'     => sanitize_file_name($file_name),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );
        
                    $attachment_id = wp_insert_attachment($attachment, $file_path);
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
                    wp_update_attachment_metadata( $attachment_id , $attachment_data);

                }
                else {
                    $attachment_id = '';
                }
            }
            
            return $attachment_id;
        }
    }
  

    /**
     *  Its validate the alpha numeric string
     * 
     *  @param string $str
     *  @return boolean
     * 
     */

     if( ! function_exists( "mh_validate_alpha_numeric") )  {
        function mh_validate_alpha_numeric( $str ) {
            $pattern = '/^[a-zA-Z0-9]+$/';
            if( ! empty( $str ) ) {
                return preg_match($pattern, $str );
            }
            return false;
        }
     }

    /** 
     * Australia driving license validation regex
     *  
     * @param string $licence_number
     * @return boolean
     * 
     */

     if( ! function_exists("mh_validate_drl")  ) {
        function mh_validate_drl( $licence_number ) {
            if (!empty($licence_number)) {
                if (preg_match("/^([A-Za-z0-9-]{1,12})$/", $licence_number)) {
                    return true;
                } 
                else {
                    return false;
                }
            } 
            else {
                return false;
            }
        }
     }
     
     /** 
      *  Validate the past date.
      *  @param string $date
      *  @return boolean
      */

     if ( ! function_exists('mh_validate_past_date') ) {
        function mh_validate_past_date($date) {
            if( ! empty($date) ) {
                
                $current_date = new DateTime();
                $current_date->setTime(0, 0, 0);
                $input_date_time = DateTime::createFromFormat('Y-m-d', $date);
        
                if ($input_date_time >= $current_date) {
                    return true;
                } 
                else {
                    return false;
                }
            }
            else {
                return false;
            }
         }
     }

      /** 
      *  Validate the passport number.
      *  @param string $passport_number
      *  @return boolean
      */

     if ( ! function_exists('mh_validate_passport_number')) {
        function mh_validate_passport_number($passport_number) {
            if (!empty($passport_number)) {
                if (preg_match("/^([A-Za-z0-9-]{1,12})$/", $passport_number)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

     /** 
      *  Validate the is pdf file .
      *  @param string $attachment_id
      *  @return boolean
      */


    if( ! function_exists("mh_check_is_pdf") ) {
        function mh_check_is_pdf( $attachment_id ) {
            $mime_type = get_post_mime_type( $attachment_id );
            return  $mime_type === 'application/pdf' ?  true : false;  
        }
    }

    /** 
      *  Validate the is image file .
      *  @param string $attachment_id
      *  @return boolean
      */

    if( ! function_exists("mh_check_is_image") ) {
        function mh_check_is_image( $attachment_id ) {
            $is_image = wp_attachment_is_image( $attachment_id );
            return $is_image  ?  true : false;  
        }
    }