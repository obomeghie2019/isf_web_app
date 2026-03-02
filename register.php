<?php
session_start();
 
?>

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php include 'db_params.php'; ?>


<!-- Verify Payment -->
<?php 

    if (!isset($_GET['tid'])){
            die('<script>window.location.href="./apply.php";</script>');
            
            exit();
    }

    else{
        
        $trx = trim($_GET['tid']);
    
        //Create query
        $qry="SELECT * FROM paymenthistory WHERE MD5(payment_ref)='".$trx."' AND payment_status='1'";
        $result=mysqli_query($cn, $qry);
        
        //Check whether the query was successful or not
        if(mysqli_num_rows($result) == 0) {
            die('<script>window.location.href="./apply.php";</script>');
            exit();
        }
    }
 
?>
<!-- End Check Payment -->


<!-- Intro -->
<section class="site-section site-section-light site-section-top themed-background-dark">
    <div class="container">
        <h1 class="text-center animation-slideDown"><i class="fa fa-arrow-right"></i> <strong>Marathon Registration</strong></h1>
        <h2 class="h3 text-center animation-slideUp">Please Complete Your Registration Below!</h2>
    </div>
</section>

<!-- END Intro -->

<!-- Log In -->
<section class="site-content site-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 site-block">
                <!-- Log In Form -->

                <form method="post" action="postreg.php" class="form-horizontal" >
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <Label>Athlete Bio-Data:</Label><br><br>
                                <input type="hidden" name="tid" value="<?php echo $trx ?>;"
                               <b>Application No:</b><input type="text" name="appnum" value="<?php echo "ISF2024-". mt_rand(1000,9999);?>" class="form-control input-lg" disabled />
                
                               <br><b>Athlete Names:</b>
                               <input type="text" name="atname" autofocus maxlength="50" class="form-control input-lg" placeholder="Enter Your Full Name Here" required />
                              
                                <br><b>Phone Number:</b>
                               <input type="text" name="phoneno" autofocus maxlength="50" class="form-control input-lg" placeholder="Enter Valid Phone Number Here" required /><br>
                               <b>State:</b>
                               <input type="text" name="state" autofocus maxlength="50" class="form-control input-lg" placeholder="Enter State Here" required /> <br><b>Local Govt. Area:</b>
                               <input type="text" name="lga" autofocus maxlength="50" class="form-control input-lg" placeholder="Enter LGA Here" required /> <br>
                               <p></p><p></p><p></p><p></p><p></p>
                               <b>Select Sex:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="gender" value="Female" required="">Female &nbsp;&nbsp;&nbsp;
                               <input type="radio" name="gender" value="Male" >Male <br><p></p><p></p>
                                <b>Running For:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="run-purpose" value="Prize" required="">Prize &nbsp;&nbsp;&nbsp;
                               <input type="radio" name="run-purpose" value="Fun" >Fun <br><p></p><p></p>
                               <b>Are Your Physical/Medically Fit for Race</b>&nbsp;&nbsp;&nbsp;<input type="radio" name="fit" value="yes" required>Yes
                               
                            </div>
                        </div>
                    </div>
                    <label>Accept Martahon Terms</label>
                    <input id="inp" type="checkbox"  name="terms"  value="" required="" >
                    <p><a href="#" style="color:#000"> By clicking Submit, You agree to your terms of ISF Marathon 2024</a></p>
                    <div class="form-group form-actions">
                        <div class="col-xs-6 text-right">
                            <button type="submit" name="regsubmit" value="Proceed" class="btn btn-sm btn-primary"><i class="fa fa-arrow-right"></i> Submit</button>

                        </div>
                    </div>
   
                </form>
                <div class="text-center">
                    <small>Already Applied? -</small> <a href="re-print.php"> Print ISF Marathon 2024 Slip!</a>
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

