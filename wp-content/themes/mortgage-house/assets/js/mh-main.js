
let signInForm = document.getElementById("mh-sign-in");
let signUpForm = document.getElementById("mh-sign-up");
let updateUserInfoForm = document.getElementById("mh-update-user-info");




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

/** Handle Update  */

if( updateUserInfoForm ) {

    updateUserInfoForm.addEventListener("submit", function(e) {

        e.preventDefault();

        let companyName = sanatizeInput( document.getElementById("edit-company-name").value );
        let primaryContactName = sanatizeInput( document.getElementById("edit-contact-name").value );
        let password =  document.getElementById("edit-password-regis").value ;
        let mobileNumber = sanatizeInput( document.getElementById("edit-mobile-num").value );
        let address = sanatizeInput( document.getElementById("edit-address").value );

        let postObject = {
            companyName,
            primaryContactName,
            password,
            mobileNumber,
            address
        };

        handleProfileUserUpdate(mh_main_script_vars.ajax_url, postObject , mh_main_script_vars.security);

    });

} 

// var inactivityTimeout = 10; // 3 minutes in seconds
// var inactivityTimer;

// /** User logout after 3 min */

// function resetInactivityTimer() {
//     clearTimeout(inactivityTimer);
//     inactivityTimer = setTimeout(logoutUser, inactivityTimeout * 1000);
// }

// async function logoutUser() {

//     let adminAjax = mh_main_script_vars.ajax_url;
//     let nonce = mh_main_script_vars.security;

//     try {
//         const response = await fetch(adminAjax , {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded',
//             },
//             body: new URLSearchParams({
//                 action: 'logout_session',
//                 nonce: nonce,
//             })    
//         });

//         if (!response.ok) {
//             console.error(`HTTP error! Status: ${response.status}`);
//             return; 
//         }

//         const data = await response.json();
//         console.log(data);

//         if (data.success && ! data.error) {
//             window.location.href = data.redirect;
//         }

//         if (!data.success && data.error) {
//             console.error(data.message);
//         }

//     }
//     catch(err) {
//         console.log(err.message);
//     }
// }

// document.addEventListener('mousemove', resetInactivityTimer);
// document.addEventListener('keypress', resetInactivityTimer);

