
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

/** Sign in error element ids  */

var signInErrorIds = {
    email: 'signin-email-error',
    password:'signin-pass-error',
}

/** Validate email address */

function validateEmailAddress( email ) {
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email.trim());
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

function renderSignInErrors( errors ) {
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
                
            }
        }
    }
    catch(err) {
        console.log(err.message);
    }
}



