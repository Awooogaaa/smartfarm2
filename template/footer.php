
<?php
// /template/footer.php
?>
    </div> <script src="../modul/js/jquery.min.js"></script>
    <script src="../modul/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Script untuk toggle sidebar di mobile
        $(document).ready(function() {
            $("#sidebarToggleBtn").on("click", function() {
                $("#sidebar").addClass("active");
            });

            $("#sidebarCloseBtn").on("click", function(e) {
                e.preventDefault();
                $("#sidebar").removeClass("active");
            });
            
            // Sembunyikan sidebar jika klik di luar area sidebar
            $(document).on('click', function(event) {
                if ($("#sidebar").hasClass('active')) {
                    if (!$(event.target).closest('.sidebar').length && !$(event.target).closest('#sidebarToggleBtn').length) {
                         $("#sidebar").removeClass("active");
                    }
                }
            });
        });
    </script>
</body>
</html>