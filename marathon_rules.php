

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<?php 
 session_start();
?>

<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-arrow-right"></i> <strong>ISF Marathon Rules & Regulations </strong></h1>
        <h2 class="h3 text-center animation-slideUp">The below rules MUST be strickly adhere to by all the atheletes that will participate in the marathon race<b></b></h2>
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
                                <h2></h2><strong>ISF Marathon (5KM) Rules and Regulations</strong></h2>
                                <p> <b>(a)</b>No use of drug before or during marathon race, if found guilty of using any drugs you will be disqualified even after completing the race. </p>
                                <p> <b>(b)</b>You must stay on the marathon race track throughout the race from START to FINISH, if you leave the race track you will be disqualified.	 </p>
                               <p> <b>(c)</b>Always make sure you wear your Bib No for easy identification. </p>
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