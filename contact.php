<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-envelope"></i> <strong>Contact Us</strong></h1>
        <h2 class="h3 text-center animation-slideUp">We will be happy to answer all your questions</h2>
    </div>
</section>
<!-- END Intro -->

<!-- Contact -->
<section class="site-content site-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 site-block">
                <div class="site-block">
                    <h3 class="h2 site-heading"><strong>Glix</strong> Schools</h3>
                    <address>
                        No 093, Orile Agege, <br>
                        Lagos State, Nigeria.<br><br>
                        <i class="fa fa-phone"></i> (+234) 7017043231<br>
                        <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)">glix@ritedev.com</a>
                    </address>
                </div>
                <div class="site-block">
                    <h3 class="h2 site-heading"><strong>About</strong> Us</h3>
                    <p class="remove-margin">
                        Glix Schools is a Student entrance management system developed by Ritedev Technologies.
                        The Software can be use by any institution.
                    </p>
                </div>
            </div>
            <div class="col-sm-6 col-md-8 site-block">
                <h3 class="h2 site-heading"><strong>Contact</strong> Form</h3>
                <form action="contact.php#form-contact" method="post" id="form-contact">
                    <div class="form-group">
                        <label for="contact-name">Name</label>
                        <input type="text" id="contact-name" name="contact-name" class="form-control input-lg" placeholder="Your name..">
                    </div>
                    <div class="form-group">
                        <label for="contact-email">Email</label>
                        <input type="text" id="contact-email" name="contact-email" class="form-control input-lg" placeholder="Your email..">
                    </div>
                    <div class="form-group">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" name="contact-message" rows="10" class="form-control input-lg" placeholder="Let us know how we can assist.."></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- END Contact -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Google Maps API + Gmaps Plugin, must be loaded in the page you would like to use maps -->
<script src="//maps.google.com/maps/api/js?sensor=true"></script>
<script src="js/helpers/gmaps.min.js"></script>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/contact.js"></script>
<script>$(function(){ Contact.init(); });</script>

<?php include 'inc/template_end.php'; ?>