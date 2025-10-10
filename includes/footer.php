<style>
    .footer {
        background-color: #343a40;
        color: #fff;
        padding: 40px 0;
    }
    .footer h5 {
        color: #fff;
        margin-bottom: 20px;
    }
    .footer p, .footer a {
        color: #ccc;
    }
    .footer a {
        text-decoration: none;
    }
    .footer a:hover {
        color: #fff;
        text-decoration: underline;
    }
    .footer .social-icons a {
        color: #fff;
        margin: 0 10px;
        font-size: 20px;
        transition: color 0.3s;
    }
    .footer .social-icons a:hover {
        color: #007bff;
    }
    .footer .footer-bottom {
        border-top: 1px solid #444;
        padding-top: 20px;
        margin-top: 20px;
    }
</style>

</div> <!-- /container -->

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>About Us</h5>
                <p>We offer the best cars for rent at affordable prices. Our mission is to provide a seamless and enjoyable car rental experience.</p>
            </div>
            <div class="col-md-2">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cars.php">Cars</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Contact Us</h5>
                <p>Email: contact@carrental.com<br>Phone: (123) 456-7890</p>
            </div>
            <div class="col-md-3">
                <h5>Follow Us</h5>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Car Rental Management System. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Font Awesome for social media icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>