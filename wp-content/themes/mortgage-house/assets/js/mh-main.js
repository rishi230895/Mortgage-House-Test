let signInForm = document.getElementById("mh-sign-in");
let signUpForm = document.getElementById("mh-sign-up");
let editForm = document.getElementById("mh-edit-details");
let passport = document.getElementById("passport-upload");
let dLicense = document.getElementById("driver-license-upload");
let dlFileName = document.getElementById("dl-file-name");
let dlFileSize = document.getElementById("dl-file-size");
let passportFileName = document.getElementById("passport-file-name");
let passportFileSize = document.getElementById("passport-file-size");

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
            errors.email = 'Email Address is mandatory';
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
            errors.password = 'Password is mandatory';
        }
        else {
            if( password.length < 8 ) {
                isError =  true;
                errors.password = 'Requires atleast 1 uppercase, 1 lowercase, 1 number, 1 special character, and be atleast 8 characters long';
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
            renderSignInErrors(errors, signInErrorIds);
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

        let passportFile =  formData.get("passport-upload");
        let passportNumber =  formData.get("passportnum");
        let passportExpDate =  formData.get("passportexpnum");

        let drlFile =  formData.get("driver-license-upload");
        let drlNumber =  formData.get("dlnum");
        let drlExpDate =  formData.get("dlexpnum");

        let isError = true;
        let errors = {
            companyName:'',
            primaryContactName: '',
            mobileNumber: '',
            emailAddress: '',
            password: '',
            address: '',
            passportFile: '',
            passportNumber: '',
            passportExpDate: '',
            drlFile: '',
            drlNumber: '',
            drlExpDate: ''
        };

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

        var formData = new FormData();

        cleanErrors(signUpErrorIds);

        // Validate company name
        if( isEmpty(companyName) ) {
            isError = true;
            errors.companyName = 'Company name is mandatory';
            console.log(errors.companyName)
        }

        // Validate primary contact name
        if( isEmpty(primaryContactName) ){
            isError = true;
            errors.primaryContactName = 'Primary contact name is mandatory';
            console.log(errors.primaryContactName)
        }

        // Validate mobile number
        if( isEmpty( mobileNumber) ) {
            isError =  true;
            errors.mobileNumber = 'Mobile Number is mandatory';
            console.log(errors.mobileNumber);
        }
        else {
            if( ! validateMobileNumber(mobileNumber) ) {
                isError =  true;
                errors.mobileNumber = 'Invalid mobile number';
                console.log(errors.mobileNumber);
            }
        }

        // Validate email address
        if( isEmpty( emailAddress) ) {
            isError =  true;
            errors.emailAddress = 'Email Address is mandatory';
            console.log(errors.emailAddress)
        }
        else {
            if( ! validateEmailAddress(emailAddress) ) {
                isError =  true;
                errors.email = 'Invalid email address.';
                console.log(errors.email)
            }
        }

        /** Validate Password */
        if( isEmpty( password ) ) {
            isError =  true;
            errors.password = 'Password is mandatory';
            console.log(errors.password)
        }
        else {
            if( ! validatePassword(password) ) {
                isError =  true;
                errors.password = 'Requires atleast 1 uppercase, 1 lowercase, 1 number, 1 special character, and be atleast 8 characters long';
                console.log(errors.password)
            } 
        }

        // Validate address
        if( isEmpty(address) ){
            isError = true;
            errors.address = 'Address is mandatory';
            console.log(errors.address)
        }

        // Validate passport file
        if( isFileInputEmpty(passport) ){
            isError = true;
            errors.passportFile = 'Please upload your passport';
            console.log(errors.passportFile);
        }

        // Validate passport number
        if( isEmpty(passportNumber) ){
            isError = true;
            errors.passportNumber = 'Passport number is mandatory';
            console.log(errors.passportNumber);
        }else{
            if( ! isAlphanumeric(passportNumber) ){
                isError = true;
                errors.passportNumber = 'Passport number must be alphanumeric';
                console.log(errors.passportNumber)
            }
        }

        // Validate passport expiry date
        if( isEmpty(passportExpDate) ){
            isError = true;
            errors.passportExpDate = 'Please select your passport expiry date';
            console.log(errors.passportExpDate);
        }

        // Validate driving license file
        if( isFileInputEmpty(dLicense) ){
            isError = true;
            errors.drlFile = 'Please upload your driving license';
            console.log(errors.drlFile);
        }

        // Validate driving license number
        if( isEmpty(drlNumber) ){
            isError = true;
            errors.drlNumber = 'Driving license number is mandatory';
            console.log(errors.drlNumber);
        }else{
            if( ! isAlphanumeric(drlNumber) ){
                isError = true;
                errors.drlNumber = 'Driving license number must be alphanumeric';
                console.log(errors.drlNumber)
            }
        }

        // Validate driving license expiry date
        if( isEmpty(drlExpDate) ){
            isError = true;
            errors.drlExpDate = 'Please select your driving license expiry date';
            console.log(errors.drlExpDate);
        }

        /** Post Form Data to the server.  */


        if( ! isError && checkErrorsMessages( errors ) ) {

        }else{
            renderSignInErrors(errors, signUpErrorIds);
        }

    
    })
}

/** Handle file upload */

/** passport upload */
if( passport ){
    passport.addEventListener('change', function(e){
        handleFileUpload(e, passportFileName, passportFileSize)
    });
}

/** driving license upload */
if( dLicense ) {
    dLicense.addEventListener('change', function(e){
        handleFileUpload(e, dlFileName, dlFileSize)
    });
}

/** Handle Edit details submit */
if( editForm ) {
    editForm.addEventListener('submit', function(e) {

        e.preventDefault();

        var formData = new FormData(editForm);

        let companyName =  formData.get("companyname");      
        let primaryContactName =  formData.get("contactname");
        let mobileNumber =  formData.get("mobile");
        let password =  formData.get("passwordregis");
        let address =  formData.get("address");

        let isError = true;
        let errors = {
            companyName:'',
            primaryContactName: '',
            mobileNumber: '',
            password: '',
            address: ''
        };

        formData.append( 'company_name' , companyName);
        formData.append( 'primary_contact_name' , primaryContactName );
        formData.append( 'mobile_number' , mobileNumber);
        formData.append( 'password', password );
        formData.append( 'address', address );

        var formData = new FormData();

        cleanErrors(editErrorIds);

        // Validate company name
        if( isEmpty(companyName) ) {
            isError = true;
            errors.companyName = 'Company name is mandatory';
            console.log(errors.companyName)
        }

        // Validate primary contact name
        if( isEmpty(primaryContactName) ){
            isError = true;
            errors.primaryContactName = 'Primary contact name is mandatory';
            console.log(errors.primaryContactName)
        }

        // Validate mobile number
        if( isEmpty( mobileNumber) ) {
            isError =  true;
            errors.mobileNumber = 'Mobile Number is mandatory';
            console.log(errors.mobileNumber);
        }
        else {
            if( ! validateMobileNumber(mobileNumber) ) {
                isError =  true;
                errors.mobileNumber = 'Invalid mobile number';
                console.log(errors.mobileNumber);
            }
        }

        /** Validate Password */
        if( isEmpty( password ) ) {
            isError =  true;
            errors.password = 'Password is mandatory';
            console.log(errors.password)
        }
        else {
            if( ! validatePassword(password) ) {
                isError =  true;
                errors.password = 'Requires atleast 1 uppercase, 1 lowercase, 1 number, 1 special character, and be atleast 8 characters long';
                console.log(errors.password)
            } 
        }

        // Validate address
        if( isEmpty(address) ){
            isError = true;
            errors.address = 'Address is mandatory';
            console.log(errors.address)
        }

        /** Post Form Data to the server.  */


        if( ! isError && checkErrorsMessages( errors ) ) {

        }else{
            renderSignInErrors(errors, editErrorIds);
        }

    
    })
}