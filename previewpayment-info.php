

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>

<?php 
 session_start();
?>

<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-arrow-right"></i> <strong>Marathon Registration</strong></h1>
        <h2 class="h3 text-center animation-slideUp">Registration requires a payment of NGN 500</h2>
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


                    <b>ISF Reg. Number:</b><input type="text" name="codex" value="<?php echo "ISF2024-". mt_rand(100,999);?>" class="form-control input-lg" disabled />

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-pen"></i></span>
                                <input type="text" name="email" class="form-control input-lg" placeholder="Enter an active email addrees" required />    
                            </div>
                        </div>
                    </div>
                            <div class="form-group">
                             <div class="col-xs-12">
                            <div class="input-group">
                             <span class="input-group-addon"><i class="gi gi-pen"></i></span>
                                <input type="text" name="phoneno" class="form-control input-lg" placeholder="Enter an active mobile phone number" required />
                            </div>

                        </div>
                    </div>


                    <div class="form-group form-actions">
                        <div class="col-xs-6 text-right">
                            <button type="submit" name="regsubmit" value="Proceed" class="btn btn-sm btn-primary"><i class="fa fa-arrow-right"></i> CONTINUE >></button>
                        </div>
                    </div>

   
                </form>
                <div class="text-center">
                    <small>Already Registered? -</small> <a href="re-print.php"> Print ISF Marathon 2021 Slip!</a>
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