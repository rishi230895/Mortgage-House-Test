<?php 

    /** Get all user information */

    $current_user_id = get_current_user_id();

    $company_name = get_user_meta( $current_user_id, "company_name", true );
    $primary_contact_name = get_user_meta( $current_user_id, "primary_contact_name", true );
    $mobile_number = get_user_meta( $current_user_id, "mobile_number", true );
    $email_address = get_user_meta( $current_user_id, "email_address", true );
    $address = get_user_meta( $current_user_id, "address", true );
    $passport_number = get_user_meta( $current_user_id, "passport_number", true );
    $passport_exp_date = get_user_meta( $current_user_id, "passport_exp_date", true );
    $drl_number = get_user_meta( $current_user_id, "drl_number", true );
    $drl_exp_date = get_user_meta( $current_user_id, "drl_exp_date", true );
    $passport_file_attach_id = get_user_meta( $current_user_id, "passport_file_attach_id", true );
    $drl_file_attach_id = get_user_meta( $current_user_id, "drl_file_attach_id", true );
    $switch_two_factor_auth = get_user_meta( $current_user_id, "switch_two_factor_auth", true );


    

?>
<div class="relative z-10 edit-popup" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="open">
    
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full justify-center p-4 text-center items-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"  @click="open = ! open"></div>
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold tracking-tight text-2xl">Edit your Profile</h2>
                        <button class="py-1 pl-2" type="button" @click="open = ! open"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form method="post" class="space-y-3" id="mh-update-user-info">
                        <div class="space-y-2">
                            <label for="edit-company-name">Company Name<span class="text-red required">*</span></label>
                            <input class="mt-2 peer" type="text" name="companyname" id="edit-company-name" autocomplete="off" placeholder="Mortgage Pvt Ltd" value="<?php echo $company_name; ?>" />
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="edit-company-name-error">Company name is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="edit-contact-name">Primary Contact Name<span class="text-red required">*</span></label>
                            <input class="mt-2 peer" type="text" name="contactname" id="edit-contact-name" autocomplete="off" placeholder="Contact Name" value="<?php echo $primary_contact_name; ?>" />
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="edit-contact-name-error">Primary contact name is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="edit-mobile-num">Mobile Number<span class="text-red required">*</span></label>
                            <span class="relative">
                                <span class="mobile-code font-medium text-gray-500 h-full rounded-tl rounded-bl absolute top-[1px] left-[1px] p-1 px-2 flex justify-center items-center bg-gray-200">+61</span>
                                <input class="mt-2 peer" type="tel" name="mobile" id="edit-mobile-num" autocomplete="off" placeholder="04XX XXX XXX" value="<?php echo $mobile_number; ?>" />
                            </span>
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="edit-mobile-error">Mobile number is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="edit-email-regis">Email Address<span class="text-red required">*</span></label>
                            <input disabled class="mt-2 peer" type="email" name="emailregis" id="edit-email-regis" autocomplete="off" placeholder="username@email.com" />
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="edit-regis-email-error">Please provide a valid email</p>
                        </div>
                        <div class="space-y-2">
                            <label for="edit-password-regis">Password<span class="text-red required">*</span></label>
                            <input class="mt-2" type="password" name="passwordregis" id="edit-password-regis" autocomplete="off" placeholder="Password" />
                            <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="edit-regis-pass-error">Password is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="edit-address">Address<span class="text-red required">*</span></label>
                            <textarea name="address" id="edit-address" cols="30" rows="10">
                                <?php echo trim($address); ?>
                            </textarea>
                            <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="edit-address-error">Address is required</p>
                        </div>
                        <button type="submit" class="btn btn-md btn-blue w-full capitalize">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
