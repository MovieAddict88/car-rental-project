</div> <!-- End of content div from navbar.php -->
</div> <!-- End of wrapper div from header.php -->

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS for sidebar toggle -->
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        var sidebarCollapse = document.getElementById('sidebarCollapse');
        var sidebar = document.getElementById('sidebar');

        sidebarCollapse.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    });
</script>

</body>
</html>