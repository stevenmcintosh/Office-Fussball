    </div>



    <!-- jQuery, loaded in the recommended protocol-less way -->
    <!-- more http://www.paulirish.com/2010/the-protocol-relative-url/ -->
    <script src="<?php echo URL; ?>public/js/jquery.1.11.2.min.js"></script>
    <!-- our JavaScript -->
   
    <script src="<?php echo URL; ?>public/js/bootstrap.min.js"></script>
    <script src="<?php echo URL; ?>public/js/datatables.min.js"></script>
    <script src="<?php echo URL; ?>public/js/application.js"></script>
    <?php
    if(!empty($footerJsFiles)) {
        foreach($footerJsFiles as $jsFile) {
            echo "<script src=\"" . URL . "public/js/" . $jsFile . ".js\"></script>";
        }
    } ?>
</body>
</html>
