<div class="grid grid-cols-1 lg:grid-cols-2 lg:h-screen lg:overflow-hidden">
    <div class="lg:h-screen relative hidden lg:block">
        <div class="bg-blue-950 bg-opacity-80 absolute inset-0"></div>
        <img class="h-full w-full object-cover object-top hidden lg:block" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/mortgage-bg1.jpg" alt="">
        <div class="absolute top-9 left-5 text-white hidden lg:block">
            <a href="#" class="flex items-center justify-center gap-3 uppercase font-black tracking-wide text-lg text-center">
                <img width="60" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/mortgage.png" alt="">
                <p class="flex flex-col items-baseline">
                    <span>Mortgage House</span>
                    <small class="font-medium capitalize text-xs">Mortgage Maven</small>
                </p>
            </a>
        </div>
        <div class="absolute bottom-9 left-5 text-white hidden lg:block">
            <p class="text-sm">Copyright © 2023 Mortgage Pvt Ltd. All Rights Reserved.</p>
        </div>
    </div>
    <div class="px-4 lg:px-12 xl:px-20 py-10 lg:overflow-hidden" x-data="{activeTab: 'register'}">
        <div class="grid grid-cols-2 gap-2 z-10 lg:mx-auto bg-white shadow rounded-md p-2 sticky top-0">
            <button class="btn-btn-full py-2 font-semibold rounded-md capitalize" :class="{'bg-blue-950 text-white': activeTab === 'register'}" @click="activeTab = 'register'">Register</button>
            <button class="btn-btn-full py-2 font-semibold rounded-md capitalize" :class="{'bg-blue-950 text-white': activeTab === 'signin'}" @click="activeTab = 'signin'">sign in</button>
        </div>
        <div class="mt-4 rounded-md">
            <!-- register form -->
            <div class="lg:mx-auto flex-1" :class="activeTab === 'register' ? 'lg:pb-12 form-scroll' : '' " x-data="{register: false}">
                <div class="bg-white shadow py-5 px-3 lg:p-10 rounded-md lg:overflow-y-auto lg:h-full" x-show="activeTab === 'register'">
                    <h2 class="font-bold tracking-tight text-2xl mb-4">Create your Profile</h2>
                    <form method="post" class="space-y-3">
                        <div class="space-y-2">
                            <label for="company-name">Company Name<span class="text-red required">*</span></label>
                            <input class="mt-2 peer" type="text" name="companyname" id="company-name" autocomplete="off" placeholder="Mortgage Pvt Ltd" />
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="company-name-error">Company name is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="contact-name">Primary Contact Name<span class="text-red required">*</span></label>
                            <input class="mt-2 peer" type="text" name="contactname" id="contact-name" autocomplete="off" placeholder="Contact Name" />
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="contact-name-error">Primary contact name is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="mobile-num">Mobile Number<span class="text-red required">*</span></label>
                            <span class="relative">
                                <span class="mobile-code font-medium text-gray-500 h-full rounded-tl rounded-bl absolute top-[1px] left-[1px] p-1 px-2 flex justify-center items-center bg-gray-200">+61</span>
                                <input class="mt-2 peer" type="tel" name="mobile" id="mobile-num" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" autocomplete="off" placeholder="04XX XXX XXX" />
                            </span>
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="mobile-error">Mobile number is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="email-regis">Email Address<span class="text-red required">*</span></label>
                            <input class="mt-2 peer" type="email" name="emailregis" id="email-regis" autocomplete="off" placeholder="username@email.com" />
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="regis-email-error">Please provide a valid email</p>
                        </div>
                        <div class="space-y-2">
                            <label for="password-regis">Password<span class="text-red required">*</span></label>
                            <input class="mt-2" type="password" name="passwordregis" id="password-regis" autocomplete="off" placeholder="Password" />
                            <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="regis-pass-error">Password is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="address">Address<span class="text-red required">*</span></label>
                            <textarea name="address" id="address" cols="30" rows="10"></textarea>
                            <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="address-error">Address is required</p>
                        </div>
                        <div class="space-y-2">
                            <label for="passport" class="block text-sm font-medium leading-6 text-gray-900">Passport</label>
                            <div>
                                <div class="rounded-lg border border-dashed border-gray-900/25 ">
                                    <label for="passport-upload" class="relative cursor-pointer px-6 py-5 flex flex-col lg:flex-row gap-2 items-center">
                                        <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="flex flex-col items-center text-center lg:items-start lg:text-left gap-1">
                                            <div class="flex text-sm leading-6 text-gray-600">
                                                <span class="rounded-md bg-white font-semibold text-blue-950 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-950 focus-within:ring-offset-2 hover:text-blue-900">Upload Passport</span>
                                                <input id="passport-upload" name="passport-upload" type="file" accept=".jpg, .jpeg, .png" class="sr-only">
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs leading-5 text-gray-600">PNG, JPG up to 10MB</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="passport-error">Please upload your passport</p>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-2">
                                <div class="flex-1">
                                    <input class="mt-2" type="number" name="passportnum" id="passport-num" autocomplete="off" placeholder="Enter Passport Number" />
                                    <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="passport-num-error">Passport Number is required</p>
                                </div>
                                <div class="lg:w-1/3">
                                    <input class="mt-2" type="date" name="passportexpnum" id="passport-exp-num" autocomplete="off" />
                                    <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="passport-exp-error">Passport expiry date is required</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label for="driver-license" class="block text-sm font-medium leading-6 text-gray-900">Driver License</label>
                            <div>
                                <div class="rounded-lg border border-dashed border-gray-900/25 ">
                                    <label for="driver-license-upload" class="lg:relative cursor-pointer px-6 py-5 flex flex-col lg:flex-row gap-2 items-center">
                                        <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="flex flex-col items-center text-center lg:items-start lg:text-left gap-1">
                                            <div class="flex text-sm leading-6 text-gray-600">
                                                <span class="rounded-md bg-white font-semibold text-blue-950 focus-within:outline-none focus-within:ring-2 focus-within:ring-bluetext-blue-950 focus-within:ring-offset-2 hover:text-blue-900">Upload driver license</span>
                                                <input id="driver-license-upload" name="driver-license-upload" type="file" accept=".jpg, .jpeg, .png" class="sr-only">
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs leading-5 text-gray-600">PNG, JPG up to 10MB</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="license-error">Please upload your driving license</p>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-2">
                                <div class="flex-1">
                                    <input class="mt-2" type="number" name="dlnum" id="dl-num" autocomplete="off" placeholder="Enter driving license number" />
                                    <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="dl-num-error">Driving license number is required</p>
                                </div>
                                <div class="lg:w-1/3">
                                    <input class="mt-2" type="date" name="dlexpnum" id="dl-exp-num" autocomplete="off" />
                                    <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="dl-exp-error">Driving license expiry date is required</p>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-md btn-blue w-full capitalize">Create profile</button>
                    </form>
                </div>
            </div>
            <!-- signin form -->
            <div class="lg:mx-auto flex-1" x-data="{signin: true}">
                <div class="bg-white shadow  py-5 px-3 lg:p-10 rounded-md" x-show="activeTab === 'signin'">
                    <h2 class="font-bold tracking-tight text-2xl mb-4">Sign in to your profile</h2>
                    <form method="post" class="space-y-3">
                        <div class="space-y-2">
                            <label for="email">Email Address<span class="text-red required">*</span></label>
                            <input class="mt-2 peer" type="email" name="email" id="email" autocomplete="off" placeholder="username@email.com" />
                            <p class="invisible peer-invalid:visible text-red-600 font-medium text-[10px] leading-[10px]" id="signin-email-error">Please provide a valid email</p>
                        </div>
                        <div class="space-y-2">
                            <label for="password">Password<span class="text-red required">*</span></label>
                            <input class="mt-2" type="password" name="password" id="password" autocomplete="off" placeholder="Password" />
                            <p class="invisible text-red-600 font-medium text-[10px] leading-[10px]" id="signin-pass-error">Password is required</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="rememberme" id="remember-me" class="w-4 h-4 rounded text-blue-950 focus:ring-blue-900">
                            <label for="remember-me" class="capitalize">remember me</label>
                        </div>
                        <button type="submit" class="btn btn-md btn-blue w-full capitalize">sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>