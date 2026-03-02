<?php
require 'db_params.php';
error_reporting(0);
if(isset($_POST['submit'])){
$department = $_POST['department'];
$quest = $_POST['quest'];
if ($department==""){
 $er1 = '*Field Empty';
}
elseif ($quest==""){
 $er = '*Field Empty';
}
else{

$department = $_POST['department'];
$quest = $_POST['quest'];

$query = "INSERT INTO suggest (class, question) VALUES ('$department', '$quest')";
if ($query){
echo '<script type="text/javascript">
alert ("Your Question ' . $quest. ' has successfully been added to the category of ' . $department. ' class. Thanks");
</script>';
}

mysql_query($query) or die ('Error, query failed');

mysql_close();
}


}
?> 

<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>

<!-- Login Alternative Row -->
<div class="container">
    <div class="row">
        <div class="col-md-5 col-md-offset-1">
            <div id="login-alt-container">
                <!-- Title -->
                <h1 class="push-top-bottom">
                    <i class="fa fa-book"></i> <strong><?php echo $template['name']; ?></strong><br>
                    <small>Welcome to <?php echo $template['name']; ?> Question Suggestion Area!</small>
                </h1>
                <!-- END Title -->

                <!-- Key Features -->
                <ul class="fa-ul text-muted">
                    <li><i class="fa fa-check fa-li text-success"></i> Submit Questions with answers</li>
                    <li><i class="fa fa-check fa-li text-success"></i> Waec, Neco, Nabteb Questions are allowed</li>
                    <li><i class="fa fa-check fa-li text-success"></i> Specify Question year (Brochure)</li>
                    <li><i class="fa fa-check fa-li text-success"></i> Select Class to post questions to </li>
                    <li><i class="fa fa-check fa-li text-success"></i> Don't add same question to same class</li>
                    <li><i class="fa fa-check fa-li text-success"></i> Your IP would be ban if too much request submitting same quesiton</li>
                    <li><i class="fa fa-check fa-li text-success"></i> Enjoy!</li>
                </ul>
                <!-- END Key Features -->

                <!-- Footer -->
                <footer class="text-muted push-top-bottom">
                    <small><span id="year-copy"></span> &copy; <a href="http://goo.gl/TDOSuC" target="_blank"><?php echo $template['name'] . ' ' . $template['version']; ?></a></small>
                </footer>
                <!-- END Footer -->
            </div>
        </div>
        <div class="col-md-5">
            <!-- Login Container -->
            <div id="login-container">
                <!-- Login Title -->
                <div class="login-title text-center">
                    <h1><strong>Suggestion Form</strong></h1>
                </div>
                <!-- END Login Title -->

                <!-- Login Block -->
                <div class="block push-bit">
                    <!-- Login Form -->
                    <form method="post" action="squestion.php" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                    <select id="department" name="department" class="form-control input-lg">
<option value="science">Science Class</option>
        <option value="art">Art Class</option>
        <option value="commercial">Commercial Class</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-check"></i></span>
                                    <span style="color:red; font-size:11px;"><?php echo $er; ?></span>
                                    <textarea name="quest" rows="8" cols="89" class="form-control input-lg">

                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-actions">
                            <div class="col-xs-4">
                            <a href="#modal-terms" data-toggle="modal" class="register-terms">Who is eligible?</a>
                            </div>
                            <div class="col-xs-8 text-right">
                                <button type="submit" name="submit" value="Submit Question" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Submit Question</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Login Form -->
                </div>
                <!-- END Login Block -->
            </div>
            <!-- END Login Container -->
        </div>
    </div>
</div>
<!-- END Login Alternative Row -->

<!-- Modal Terms -->
<div id="modal-terms" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Who &amp; Who is Eligible</h4>
            </div>
            <div class="modal-body">
                <h4>Everyone</h4>
                <p>Everyone is eligible to submit relevant questions to the database.</p>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Terms -->

<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/log.js"></script>
<script>$(function(){ Login.init(); });</script>

<?php include 'inc/template_end.php'; ?>