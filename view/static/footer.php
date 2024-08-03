        <!-- assets/Vendor -->
        
        <script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
        <script src="assets/vendor/popper/umd/popper.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="assets/vendor/common/common.js"></script>
        <script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
        <script src="assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
        <script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

        <!-- Specific Page assets/Vendor -->
        <?php 
            if($args['js']) {
                echo $args['js'];
            }
        ?>
        <!-- Theme Base, Components and Settings -->
        <script src="assets/js/theme.js"></script>

        <!-- Theme Custom -->
        <script src="assets/js/custom.js"></script>

        <!-- Theme Initialization Files -->
        <script src="assets/js/theme.init.js"></script>

        <script src="assets/js/examples/examples.dashboard.js"></script>
       
    </body>
</html>