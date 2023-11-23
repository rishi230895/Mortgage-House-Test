
let signInForm = document.getElementById("mh-sign-in");
let signUpForm = document.getElementById("mh-sign-up");

/** Handle Signin Submit */

if( signInForm ) {

    signInForm.addEventListener('submit', function(e){
        
        e.preventDefault();

        let isError = false;
        let errors = {
            email:'',
            password:''
        };

        let emailAddress = sanatizeInput( document.getElementById("mh-email-address").value );
        let password = sanatizeInput( document.getElementById("mh-password").value );

        /** Clean All errors */

        cleanTopError('signin-top-error');
        cleanErrors();
        
        /** Validate input email address*/

        if( isEmpty( emailAddress) ) {
            isError =  true;
            errors.email = 'Email Address should not empty.';
        }
        else {
            if( ! validateEmailAddress(emailAddress) ) {
                isError =  true;
                errors.email = 'Invalid email address.';
            }
        }

        /** Validate Password */

        if( isEmpty( password ) ) {
            isError =  true;
            errors.password = 'Password should not empty.';
        }
        else {
            if( password.length < 8 ) {
                isError =  true;
                errors.password = 'Password atleast 8 characters.';
            } 
        }

        /** Fire sign in */

        if( ! isError && checkErrorsMessages( errors ) ) {
            let postData = {
                email: emailAddress,
                password: password
            };
            handleSignIn(mh_main_script_vars.ajax_url, postData , mh_main_script_vars.security);
        }
        else {
            renderSignInErrors(errors);
        }

    });
    
}

/** Handle SignUp Submit */

if( signUpForm  ) {

    signUpForm.addEventListener('submit', function(e) {

        e.preventDefault();

        var formData = new FormData(signUpForm);

        let companyName =  formData.get("companyname");      
        let primaryContactName =  formData.get("contactname");
        let mobileNumber =  formData.get("mobile");
        let emailAddress =  formData.get("emailregis");
        let password =  formData.get("passwordregis");
        let address =  formData.get("address");


        let passportFileInput = document.getElementById("passport-upload");
        let passportFile = passportFileInput.files[0];
        let passportNumber =  formData.get("passportnum");
        let passportExpDate =  formData.get("passportexpnum");

        
        let drlFileInput = document.getElementById("driver-license-upload");
        let drlFile = drlFileInput.files[0];
        let drlNumber =  formData.get("dlnum");
        let drlExpDate =  formData.get("dlexpnum");

        let isError = true;
        var formData = new FormData();

        if( isError  ) {

            formData.append( 'company_name' , companyName);
            formData.append( 'primary_contact_name' , primaryContactName );
            formData.append( 'mobile_number' , mobileNumber);
            formData.append( 'email_address' , emailAddress);
            formData.append( 'password', password );
            formData.append( 'address', address );

            formData.append( 'passport_file',  passportFile);
            formData.append( 'passport_number' , passportNumber );
            formData.append( 'passport_exp_date' , passportExpDate );

            formData.append( 'drl_file' , drlFile );
            formData.append( 'drl_number' , drlNumber );
            formData.append( 'drl_expt_date' , drlExpDate );

            /** Post Form Data to the server.  */

            handleSignUp( mh_main_script_vars.ajax_url, formData , mh_main_script_vars.security );
            
            

        }





    
    })
}
