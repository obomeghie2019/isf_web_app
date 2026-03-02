<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>



<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-arrow-right"></i> <strong>Print Athelete Slip</strong></h1>
        <h2 class="h3 text-center animation-slideUp">Reprint Requires Phone Number.</h2>
    </div>
</section>
<!-- END Intro -->

<!-- Log In -->
<section class="site-content site-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 site-block">
                <!-- Log In Form -->

                <form method="post" action="ppprint.php" class="form-horizontal" >
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-book"></i></span>
                                <input type="text" name="email" class="form-control input-lg" placeholder="Enter your E-mail: " required />
                                <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-book"></i></span>
                                <input type="text" name="phone" class="form-control input-lg" placeholder="Enter your Phone No: " required />
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-xs-6 text-right">
                            <button type="submit" name="Login" value="Print Slip" class="btn btn-sm btn-primary"><i class="fa fa-arrow-right"></i> Print Slip</button>
                        </div>
                    </div>
   
                </form>
                <div class="text-center">
                    <small>Are You An Athlete Not Yet Register? -</small> <a href="apply.php"> Apply Now!</a>
                </div>
                <!-- END Log In Form -->
            </div>
        </div>
        <hr>
    </div>
</section>
<!-- END Log In -->





<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/login.js"></script>
<script>$(function(){ Login.init(); });</script>

<?php include 'inc/template_end.php'; ?>