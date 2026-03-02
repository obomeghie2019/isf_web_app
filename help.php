

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<?php 
 session_start();
?>

<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-arrow-right"></i> <strong>ISF Marathon 2021 Registration Help Desk</strong></h1>
        <h2 class="h3 text-center animation-slideUp">You can reach us for any complaints regarding your registration;<b></b></h2>
    </div>
</section>
<!-- END Intro -->

<!-- Log In -->
<section class="site-content site-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 site-block">
                <!-- Log In Form -->

                <form method="post" action="checkPIN.php" class="form-horizontal" >
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <strong>Call Us (8:00AM - 6:00PM)</strong>
                                <p> Support Team: +2348038354389 or +2347062394657</p>
                                <strong>Email Us</strong>
                                <p> isf2021committee@gmail.com or 360globalnetwork@gmail.com</p>
                            </div>
                        </div>
                    </div>
                   

   
                </form>
                <div class="text-center">
                    <small>Already Registered? -</small> <a href="index.php"> Print ISF Marathon 2021 Slip!</a>
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