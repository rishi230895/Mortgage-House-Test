<?php  wp_footer();  ?>

<?php if(is_author() || is_front_page()) { ?>
    <footer class="<?php if(is_front_page()){ echo'block lg:hidden';} ?>">
        <div class="container">
            <p class="text-sm py-5 border-t border-t-gray-300 text-center">Copyright © 2023 Mortgage Pvt Ltd. <br class="block lg:hidden" /> All Rights Reserved.</p>
        </div>
    </footer>
<?php } ?>

</body>
</html>