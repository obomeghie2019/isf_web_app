<?php
/**
 * page_footer.php
 *
 * Author: Ritedev Tech
 *
 * The footer of each page
 *
 */

// Ensure $template exists and has a 'name' key
$template_name = isset($template['name']) ? $template['name'] : 'Iyekhei Sport Festival';
?>

<!-- Footer -->
<footer class="site-footer site-section py-5 bg-dark text-white">
    <div class="container">
        <!-- Footer Links -->
        <div class="row">

            <div class="col-sm-6 col-md-3 mb-4">
                <h5 class="footer-heading">Web/Mobile App:</h5>
                <ul class="list-unstyled">
                     <li>Developed by <a href="https://360globalnetwork.com.ng" class="text-white">36 Global Network</a></li>
                </ul>
            </div>

            <div class="col-sm-6 col-md-3 mb-4">
                <h5 class="footer-heading">Legal</h5>
                <ul class="list-unstyled">
                    <li><a href="https://www.freeprivacypolicy.com/live/36dc2f31-a0c4-44b6-a601-12c0f93415f6" class="text-white">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-sm-6 col-md-3 mb-4">
                <h5 class="footer-heading">Follow Us</h5>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="javascript:void(0)" class="text-white"><i class="fa fa-facebook fa-lg"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:void(0)" class="text-white"><i class="fa fa-whatsapp fa-lg"></i></a></li>
                </ul>
            </div>

            <!-- <div class="col-sm-6 col-md-3 mb-4">
                <h5 class="footer-heading">&copy; 2024 <a href="#" class="text-white"><?= htmlspecialchars($template_name) ?></a></h5>
                <ul class="list-unstyled">
                    <li>Associate Sponsors</li>
                    <li>Developed by <a href="https://360globalnetwork.com.ng" class="text-white">36 Global Network</a></li>
                </ul>
            </div> -->

        </div>
        <!-- END Footer Links -->
    </div>
</footer>
<!-- END Footer -->

</div>
<!-- END Page Container -->

<!-- Scroll to top link -->
<a href="#" id="to-top" class="btn btn-primary btn-lg"><i class="fa fa-angle-up"></i></a>

<!-- Optional internal CSS for footer -->
<style>
.site-footer {
    background-color: #222;
    color: #fff;
}
.site-footer a {
    color: #fff;
    text-decoration: none;
}
.site-footer a:hover {
    color: #28a745;
    text-decoration: underline;
}
.footer-heading {
    font-weight: 700;
    margin-bottom: 15px;
}
</style>
