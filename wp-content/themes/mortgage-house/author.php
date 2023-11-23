<?php 
    get_header();
?>

<div class="container">
    <div class="w-2/3 mx-auto my-12">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold tracking-tight text-gray-500">Profile Dashboard</h1>
            <div class="flex gap-4 items-center">
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
        <div class="bg-white shadow p-10 rounded">
            <div class="flex flex-col gap-2 mb-10">
                <h2 class="text-3xl font-extrabold text-blue-950">Mortgage House Pvt Ltd</h2>
                <h3 class="text-xl font-bold text-gray-800">Citizen Jane</h3>
            </div>
            <!-- information block -->
            <div class="space-y-7">
                <!-- general information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-b-gray-300 pb-2">General Information</h4>
                    <ul class="flex flex-col gap-y-3 text-base py-5 last:pb-0">
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-phone text-gray-500 w-[20px]"></i>
                            <a href="tel:0412 345 678"><span>+61 </span>0412 345 678</a>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-envelope text-gray-500 w-[20px]"></i>
                            <a href="mailto:mortgage@email.com">mortgage@email.com</a>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-location-dot text-gray-500 w-[20px]"></i>
                            <span>93 Seiferts Rd, Adelaide Park, Queensland - 4703</span>
                        </li>
                    </ul>
                </div>
                <!-- document information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-b-gray-300 pb-2">Document Information</h4>
                    <ul class="flex flex-col gap-y-6 text-base py-5 last:pb-0">
                        <li class="flex items-baseline gap-2">
                            <i class="fa-solid fa-passport text-gray-500 w-[20px]"></i>
                            <div class="flex flex-col items-start gap-1">
                                <span class="capitalize"><b>Passport Number: </b>H0170966</span>
                                <span class="capitalize"><b>Expiry Date: </b>01/03/2024</span>
                                <div x-data="{ docOpen: false }" @keydown.escape="docOpen = false">
                                    <button @click="docOpen = ! docOpen" type="button" class="btn btn-sm btn-blue mt-2">View Passport</button>
                                    <?php get_template_part('template-parts/document-popup', '', array('title' => 'Your Passport')); ?>
                                </div>
                            </div>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <i class="fa-regular fa-id-card text-gray-500 w-[20px]"></i>
                            <div class="flex flex-col items-start gap-1">
                                <span class="capitalize"><b>Driving License Number: </b>6437772</span>
                                <span class="capitalize"><b>expiry date: </b>01/03/2024</span>
                                <div x-data="{ docOpen: false }" @keydown.escape="docOpen = false">
                                    <button @click="docOpen = ! docOpen" type="button" class="btn btn-sm btn-blue mt-2">View Driving License</button>
                                    <?php get_template_part('template-parts/document-popup', '', array('title' => 'Your Driving License')); ?>
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