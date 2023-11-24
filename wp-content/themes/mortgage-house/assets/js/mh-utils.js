
/** Disable the previous dates on register.*/

window.onload = function() {
    var today = new Date().toISOString().split('T')[0];
    var dateInputs = document.querySelectorAll('input[type="date"]');
    if( dateInputs ) {
        dateInputs.forEach(element => {
            element.setAttribute('min', today);
        });
    }
}   

/** mobile number mask */
let mobileFields = jQuery('input[type="tel"]');
mobileFields.mask('0000 000 000');

/** Sign in error element ids  */

var signInErrorIds = {
    email: 'signin-email-error',
    password:'signin-pass-error',
}

var signUpErrorIds = {
    companyName: 'company-name-error',
    primaryContactName: 'contact-name-error',
    mobileNumber: 'mobile-error',
    emailAddress: 'regis-email-error',
    password: 'regis-pass-error',
    address: 'address-error',
    passportFile: 'passport-error',
    passportNumber: 'passport-num-error',
    passportExpDate: 'passport-exp-error',
    drlFile: 'license-error',
    drlNumber: 'dl-num-error',
    drlExpDate: 'dl-exp-error'
}

var editErrorIds = {
    companyName: 'edit-company-name-error',
    primaryContactName: 'edit-contact-name-error',
    mobileNumber: 'edit-mobile-error',
    password: 'edit-regis-pass-error',
    address: 'edit-address-error'
}

/** Validate email address */

function validateEmailAddress( email ) {
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email.trim());
}

/** Validate mobile number */

function validateMobileNumber( number ) {
    const mobilePattern = /^0[2-45]\d{2}\s?\d{3}\s?\d{3}$/;
    return mobilePattern.test(number);
}

/** Validate password */
function validatePassword(password) {
    const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/;
    return passwordPattern.test(password);
}

/** Validate alphanumeric */
function isAlphanumeric(inputString) {
    const alphanumericRegex = /^[a-zA-Z0-9-]+$/;
    return alphanumericRegex.test(inputString);
}

/** Handle file upload */
function handleFileUpload(event, filename, filesize) {
    console.log(filename)
    const fileInput = event.target;
    const fileName = filename;
    const fileSize = filesize;

    if (fileInput.files.length > 0) {
      const file = fileInput.files[0];

      // Check file type
      const allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
      if (!allowedFileTypes.includes(file.type)) {
        alert('Only JPG, JPEG, PNG, and PDF files are allowed.');
        fileInput.value = ''; // Clear the file input
        return;
      }

      // Check file size (max 10MB)
      const maxSizeInBytes = 10 * 1024 * 1024; // 10MB
      if (file.size > maxSizeInBytes) {
        alert('File size exceeds the maximum allowed (10MB).');
        fileInput.value = ''; // Clear the file input
        return;
      }
      console.log(fileName)
      // Display file information
      fileName.innerHTML = `<p>File Name: ${file.name}</p>`;
      fileSize.innerHTML = `<p>File Size: ${formatFileSize(file.size)}</p>`;
      console.log(file.name);
      console.log(formatFileSize(file.size))
    }
}

function formatFileSize(size) {
    const units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    let i = 0;
    while (size >= 1024 && i < units.length - 1) {
        size /= 1024;
        i++;
    }
    return `${size.toFixed(2)} ${units[i]}`;
}

/** Sanatize input */

function sanatizeInput( input ) {
    let div = document.createElement('div');
    div.innerHTML = input.trim();
    return div.textContent || div.innerText;
}

/** Validate input is empty */

function isEmpty( input ) {
    let emptyParameters = [null, undefined,''];
    if ( emptyParameters.includes(input.trim()) ) {
        return true;
    }
    return false;
}

/** Validate empty input ftype file */
function isFileInputEmpty(fileInput) {
    return fileInput.files.length === 0;
}

/** Check errors objects all keys are empty.  */

function checkErrorsMessages( obj ) {
    for (const key in obj) {
        if (obj.hasOwnProperty(key) && obj[key].trim() !== '') {
            return false;
        }
    }
    return true;
}

/** Check errors below the fields.  */ 

function cleanErrors( errors = signInErrorIds ) {
    for (const key in errors) {
        if (errors.hasOwnProperty(key) && errors[key].trim() !== '') {
            //let errorID = signInErrorIds[key];
            let errorRef = document.getElementById(errors[key]);
            if( errorRef ) {
                errorRef.innerText = ''
                errorRef.classList.add("invisible");
            }
        }
    }
}

/** Clean Top Errors  */ 

function cleanTopError(errorRef) {
    let topError = document.getElementById(errorRef);
    topError.innerHTML = '';
    topError.classList.add('invisible');
}

/** Render Validation below fields  */

function renderTopErrors(errorRef,message) {
    let topError = document.getElementById(errorRef);
    topError.innerHTML = message;
    topError.classList.remove('invisible');
}

/** Render sign errors  */

function renderSignInErrors( errors, signInErrorIds ) {
    for (const key in errors) {
        if (errors.hasOwnProperty(key) && errors[key].trim() !== '') {
            let errorID = signInErrorIds[key];
            let errorRef = document.getElementById(errorID);
            if( errorRef ) {
                errorRef.innerText = errors[key];
                errorRef.classList.remove("invisible");
            }
        }
    }
}

/** Handle Sign In */

async function handleSignIn( adminAjax , postData, nonce ) {

    try {

        const response = await fetch(adminAjax , {

            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'sign_in_action',
                nonce: nonce,
                email: postData.email,
                password: postData.password
            })
        });

        if (! response.ok) {
            let message = `HTTP error! Status: ${response.status}`;
            renderTopErrors( 'signin-top-error', message );
        }

        const data = await response.json();

        if( data ) {

            if( ( data.success) && ( ! data.error ) && ( !  data.nonce_error ) &&  ( data.fields_error.length === 0 ) ) {
                window.location.href = data.redirect;
            }
            else {

                if( data.fields_error.length === 0 ) {
                    renderTopErrors( 'signin-top-error', data.message );
                }
                else {
                    renderSignInErrors( data.fields_error );
                }  
            }
        }

    } catch (error) {
        renderTopErrors( 'signin-top-error', error.message );
    }
}

/** Handle Sign up */

async function handleSignUp( adminAjax , formData, nonce ) {

    formData.append('action', 'sign_up_action');
    formData.append('nonce', nonce);

    try {
        
        const response = await fetch(adminAjax , {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: formData,
        });

        if (! response.ok) {
            // let message = `HTTP error! Status: ${response.status}`;
            // renderTopErrors( 'signin-top-error', message );
            console.log(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log(data);

        if( ( ! data.success ) &&  ( data.error ) && ( data.fields_error.length > 0 ) ) {
            
        }
        else {
            if( (  data.success ) &&  ( ! data.error )  ) {
                if( data.redirect ) {
                    window.location.href = data.redirect;
                }
            }
        }

    }
    catch(error) {
        console.log(error.message);
    }
}

/** Handle Update */

async function handleProfileUserUpdate( adminAjax , postData, nonce ) {
    let { companyName, primaryContactName, password, mobileNumber, address } = postData;

    try {
        const response = await fetch(adminAjax , {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'update_user_info',
                nonce: nonce,
                company_name : companyName,
                primary_contact_name: primaryContactName,
                password:password,
                mobile_number:mobileNumber,
                address: address
            })    
        });

        if (! response.ok) {
            console.log(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if( ( ! data.success ) &&  ( data.error ) && ( data.fields_error.length > 0 ) ) {
            
        }
        else {
            if( (  data.success ) &&  ( ! data.error )  ) {
                window.location.reload();
            }
        }
    }
    catch(err) {
        console.log(err.message);
    }
}



