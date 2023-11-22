<?php get_footer(); ?>

<?php if(is_author() || is_front_page()) { ?>
    <footer class="<?php if(is_front_page()){ echo'lg:hidden';} ?>">
        <div class="container">
            <p class="text-sm py-3 border-t border-t-gray-300 text-center">Copyright © 2023 Mortgage Pvt Ltd. All Rights Reserved.</p>
        </div>
    </footer>
<?php } ?>

</body>
</html>