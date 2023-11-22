<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body class="bg-gray-100">
    
    <nav class="bg-white shadow text-blue-950 py-3 lg:hidden">
        <div class="container">
            <a href="#" class="flex items-center justify-center gap-3 uppercase font-black tracking-wide text-lg text-center">
                <img width="60" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/mortgage.png" alt="">
                <p class="flex flex-col items-baseline">
                    <span>
                        <?php echo __("Mortgage House", MH_THEME_DOMAIN ); ?>
                    </span>
                    <small class="font-medium capitalize text-xs"> 
                        <?php echo __("Mortgage Maven", MH_THEME_DOMAIN ); ?>
                    </small>
                </p>
            </a>
        </div>
    </nav>