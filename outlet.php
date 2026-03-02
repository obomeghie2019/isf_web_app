

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<?php 
 session_start();
?>

<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-arrow-right"></i> <strong>ISF Marathon 2021 Registration e-PIN Outlet </strong></h1>
        <h2 class="h3 text-center animation-slideUp">You can reach any of the outlet to get your e-PIN for registration<b></b></h2>
    </div>
</section>
<!-- END Intro -->

<!-- Log In -->
<section class="site-content site-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 site-block">
                <!-- Log In Form -->

                <form method="post" action="#.php" class="form-horizontal" >
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <strong>Beside Egelesor Central Mosque, Auchi</strong>
                                <p> Mr. Lucky: +234 803 075 0185</p>
                               
                                 <strong>Poly Road Opp. Fidelity Bank, Auchi</strong>
                                <p> Mr. Lukman: +234 803 383 4389</p>
                            </div>
                        </div>
                    </div>
                   

   
                </form>
                <div class="text-center">
                    <small>Already Registered? -</small> <a href="#.php"> Print ISF Marathon 2021 Slip!</a>
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