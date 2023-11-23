<?php 
    $title = $args['title'];
?>

<div class="relative z-10 document-popup" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-show="docOpen">
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"  @click="docOpen = ! docOpen"></div>
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-bold tracking-tight text-2xl"><?php echo $title; ?></h2>
                        <button class="py-1 pl-2" type="button" @click="docOpen = ! docOpen"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <img width="100%" class="object-contain max-h-[400px]" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/aus-dl.png" alt="">
                    <!-- <iframe width="100%" height="600px" allowfullscreen class="overflow-hidden" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/dummy-passport.pdf" frameborder="0"></iframe> -->
                </div>
            </div>
        </div>
    </div>
</div>
