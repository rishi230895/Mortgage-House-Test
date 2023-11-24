<?php 
    
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( home_url() );
        exit; 
    }

    get_header();

    $current_user_id = get_current_user_id();

    /** Get user information */

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

<div class="container">
    <div class="lg:w-2/3 mx-auto my-12">
        <div class="flex flex-col lg:flex-row justify-between items-start gap-4 lg:items-center mb-4">
            <h1 class="text-3xl font-bold tracking-tight text-gray-500">Profile Dashboard</h1>
            <div class="flex gap-4 w-full lg:w-auto justify-between lg:justify-normal items-center">
                <label for="toggle" class="flex items-center gap-1 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" id="toggle" class="hidden peer" checked />
                        <div class="toggle__line peer-checked:bg-blue-950 w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                        <div class="toggle__dot absolute peer-checked:translate-x-full transition-transform w-6 h-6 bg-white rounded-full shadow inset-y-1/2 -translate-y-1/2"></div>
                    </div>
                    <div class="ml-3 text-gray-700 font-medium">Enable 2FA</div>
                </label>
                <div x-data="{ open: false }" @keydown.escape="open = false">
                    <button @click="open = ! open" type="button" role="button" class="btn btn-blue btn-sm"><i class="fa-regular fa-pen-to-square mr-2"></i>Edit Details</button>
                    <?php get_template_part('template-parts/edit-popup'); ?>
                </div>
            </div>
        </div>
        <div class="bg-white shadow p-6 lg:p-10 rounded">
            <div class="flex flex-col gap-2 mb-10">
                <h2 class="text-2xl lg:text-3xl font-extrabold text-blue-950">
                    <?php echo __(ucwords( $company_name ), MH_THEME_DOMAIN ); ?>
                </h2>
                <h3 class="text-xl font-bold text-gray-800">
                    <?php echo __(ucwords( $primary_contact_name ), MH_THEME_DOMAIN ); ?>
                </h3>
            </div>
            <!-- information block -->
            <div class="space-y-7">
                <!-- general information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-b-gray-300 pb-2">
                        
                        <?php echo __('General Information', MH_THEME_DOMAIN ); ?>
                    </h4>
                    <ul class="flex flex-col gap-y-3 text-base py-5 last:pb-0">
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-phone text-gray-500 w-[20px]"></i>
                            <a href="<?php echo 'tel:'.$mobile_number; ?>"><span>
                                <?php echo __("+61", MH_THEME_DOMAIN ); ?>
                            </span>
                            <?php echo __( $mobile_number, MH_THEME_DOMAIN ); ?>
                            </a>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-envelope text-gray-500 w-[20px]"></i>
                                <a href="<?php echo 'mailto:'.$email_address; ?>">
                                <?php echo __( $email_address , MH_THEME_DOMAIN ); ?>
                            </a>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-location-dot text-gray-500 w-[20px]"></i>
                            <span>
                                <?php echo __( $address , MH_THEME_DOMAIN ); ?>
                            </span>
                        </li>
                    </ul>
                </div>
                <!-- document information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-b-gray-300 pb-2">
                        <?php echo __( 'Document Information' , MH_THEME_DOMAIN ); ?>
                    </h4>
                    <ul class="flex flex-col gap-y-6 text-base py-5 last:pb-0">
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-passport text-gray-500 w-[20px]"></i>
                            <div class="flex flex-col items-start gap-1">
                                <span class="capitalize">
                                    <b>
                                        <?php echo __( 'Passport Number:' , MH_THEME_DOMAIN ); ?>
                                    </b>
                                    <?php echo __( $passport_number , MH_THEME_DOMAIN ); ?>
                                </span>
                                <span class="capitalize">
                                    <b>
                                        <?php echo __( 'Expiry Date:' , MH_THEME_DOMAIN ); ?>
                                     </b>
                                     <?php echo __( $passport_exp_date , MH_THEME_DOMAIN ); ?>
                                </span>
                                <div x-data="{ docOpen: false }" @keydown.escape="docOpen = false">
                                    <button @click="docOpen = ! docOpen" type="button" class="btn btn-sm btn-blue mt-2">
                                        <?php echo __( 'View Passport' , MH_THEME_DOMAIN ); ?>
                                    </button>
                                    <?php get_template_part('template-parts/document-popup', '', array('title' => 'Your Passport' , 'attachment_id' => $passport_file_attach_id)); ?>
                                </div>
                            </div>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <i class="fa-regular fa-id-card text-gray-500 w-[20px]"></i>
                            <div class="flex flex-col items-start gap-1">
                                <span class="capitalize">
                                    <b> 
                                        <?php echo __( 'Driving License Number:' , MH_THEME_DOMAIN ); ?>
                                    </b>
                                    <?php echo __( $drl_number , MH_THEME_DOMAIN ); ?>
                                </span>
                                <span class="capitalize">
                                    <b>
                                    <?php echo __( 'expiry date:' , MH_THEME_DOMAIN ); ?>
                                     </b>
                                        <?php echo __( $drl_exp_date , MH_THEME_DOMAIN ); ?>
                                    </span>
                                <div x-data="{ docOpen: false }" @keydown.escape="docOpen = false">
                                    <button @click="docOpen = ! docOpen" type="button" class="btn btn-sm btn-blue mt-2">
                                        <?php echo __( 'View Driving License' , MH_THEME_DOMAIN ); ?>
                                    </button>
                                    <?php get_template_part('template-parts/document-popup', '', array('title' => 'Your Driving License', 'attachment_id' => $drl_file_attach_id)); ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    get_footer();
?>
