
<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;


if(isset($_SESSION['uid'])){
 
 
 $ids = $_GET['id'];
  include '../../config/database.php';
  #include '../../config/config.php';
  include 'header.php';


  $msg = "";

}
else{

    header("location:../pages/login.php");
}



if(isset($_POST['uset'])){

    $ids = $row['id'];									
  $pname = $row['names'];
   /*$email = $row['email'];*/
   $rdate = $row['date'];
   $phon = $row['phone'];
   $ged=$row['gender'];
   $sta = $row['state'];
   $lg = $row['LGA'];
   $pup = $row['purpose'];
   $mfit = $row['Med_Fit'];
					  
   
    $sql2 = "UPDATE atheletes SET  names='$pname', email='$email', phone='$phon', gender='$ged', state='$sta', LGA='$lg', purpose='$pup', Med_Fit ='$mfit' WHERE id = '$ids' ";
    
   if(mysqli_query($cn, $sql2)){
      
 
   $msg = "Updated successfuly!";
}
               
           else{
               $msg = "Error Updating !";
            }
 }
 
 

    ?>




  <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1><i class="fa fa-home " style="font-size:30px"></i> EDIT DETAILS</h1>
           
          
          </div>


        
   
 
          <hr></hr>
          
        
          
            <div class="box-header with-border">
            
            <?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
          </br>


 <?php 
 
 $sql2= "SELECT * FROM atheletes WHERE id = $ids";
			  $result = mysqli_query($cn,$sql2);
			  if(mysqli_num_rows($result) > 0){
				  while($row = mysqli_fetch_assoc($result)){  
				  if(isset($row['id'])){
    $pid = $row['id'];				      
     $phon = $row['phone'];                 
  $pname = $row['names'];
   $email = $row['email'];
   $rdate = $row['date'];
   $ged=$row['gender'];
   $sta = $row['state'];
   $lg = $row['LGA'];
    $pup = $row['purpose'];
   $mfit = $row['Med_Fit'];
   
					  
				  }else{
					}
?>

     <form class="form-horizontal" action="card-edit.php?id=<?php echo $ids;?>" method="POST" >

           <legend> Athelete Data Update</legend>
		   
		<div class="form-group">
        <input type="hidden" name="id"  value="<?php echo $ids;?>" class="form-control">
        </div>
       
        <div class="form-group">
         <label>Athelet Names:</label>
     <input type="text" name="pname" placeholder="Full Names"  value="<?php echo $pname;?>"  class="form-control">
        </div>
       
         <div class="form-group">
             <label>Athelet e-Mail:</label>
        <input type="text" name="email"  placeholder="Email"   value="<?php echo $email;?>"  class="form-control">
        </div>

        <div class="form-group">
        <input type="hidden" name="date"    value="<?php echo $rdate;?>" readonly class="form-control">
        </div>
        

        <div class="form-group">
            <label>Athelete Phone No:</label>
        <input type="text" name="phone" placeholder="Enter Phone Number" value="<?php echo $phon;?>" class="form-control">
        </div>

        <div class="form-group">
            <label>Athelete Gender:</label>
        <input type="text" name="gender" placeholder="Enter Sex"  value="<?php echo $ged;?>"    class="form-control">
        </div>

        <div class="form-group">
            <label>Athelete State of Origin:</label>
        <input type="text" name="state"  placeholder="Enter State"  value="<?php echo $sta;?>"  class="form-control">
        </div>
        
     <div class="form-group">
         <label>Athelete LGA of Origin:</label>
        <input type="text" name="lga"  placeholder=" Enter L.G.A"  value="<?php echo $lg;?>"   class="form-control">
        </div>

        <div class="form-group">
            <label>Medically Fit?:</label>
        <input type="text" name="fit" placeholder="Medically Fit"   value="<?php echo $mfit;?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Run For::</label>
        <input type="text" name="PIN" placeholder="e-PIN"   value="<?php echo $pup;?>" readonly class="form-control">
        </div>
        
    <button style="" type="submit" class="btn btn-success" name="uset" disabled> <i class="fa fa-send"></i>&nbsp; Update Details </button>
    </form>

<?php    
          }
          }
?>


    </div>
   </div>

   </div>
  </div>
  </section>
</div>

