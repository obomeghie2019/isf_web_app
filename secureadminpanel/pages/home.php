<?php
 session_start();
if(isset($_SESSION['uid'])){
 
 
  include 'config.php';
  #include '../../config/config/config.php';
 include 'users_query.php';
  include 'header.php';
  $msg = "";

}
else{

    header("location:../pages/login.php");
}



?>
  
 

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  
  

  <link rel="stylesheet" href=" https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.19/css/dataTables.jqueryui.min.css">
  <link rel="stylesheet" href=" https://cdn.datatables.net/buttons/1.5.6/css/buttons.jqueryui.min.css">



  

  <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href=" https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap.min.css">
  <link rel="stylesheet" href="">
 
  
    
    



  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
 

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.jqueryui.min.js"></script>
   
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
 
 
 
 <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Welcome Admin!</h1>
          </div>






          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Registered Atheletes</h4>
                  </div>
                  <div class="card-body">
                  <?php echo $totalu;?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4><a href="card.php"> Update Request</a></h4>
                  </div>
                 
                </div>
              </div>
            </div>
          
            
            </br>
         
            
            
            
            
            
            <h4 align="center">ISF 2024 Registered Atheletes</h4>
            
            </br>   </br>
            
            
              <?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>
          
          
          <div class="col-md-12 col-sm-12 col-sx-12">
               <div class="table-responsive">
                     <table class="display"  id="example">
                                    <thead>
                                        <tr>
                                             <th style="display:none">id</th>
                                            <th>NAMES</th>
                                          <!--  <th>EMAIL</th>-->
					                       <th>REG DATE </th>
                                             <th>PHONE NO</th>
                                            <th>GENDER</th>
                                             <th>STATE</th>
                                             <th>LGA</th>
                                              <th>RUN FOR</th>
                                             <th>FIT</th>
                                             
                                        </tr>
                                    </thead>
                                    <tbody>


                                    <?php 
                                    $sql= "SELECT * FROM atheletes ORDER BY id LIMIT 10";
			  $result = mysqli_query($conn,$sql);
			  if(mysqli_num_rows($result) > 0){
				  while($row = mysqli_fetch_assoc($result)){  
				  if(isset($row['id']) ){
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
					  
				  }else{
					 
				  }
				  
        
				  ?>
				

                            
                                        <tr class="odd gradeX">
                                      
                                       <form class="form-horizontal" action="history.php" method="POST" enctype="multipart/form-data" >
                                      
                                      
                                      
                                      <td style="display:none"><input name="id" value="<?php echo $row['id'];?>"></td>
                                      
                                      
                                            <td><?php echo $row['names'];?></td>
                                            <!--<td><?php echo $row['email'];?></td>-->
                                            <td><?php echo $row['date'];?></td>
                                            <td><?php echo $row['phone'];?></td>
                                            <td><?php echo $row['gender'];?></td>
                                            <td><?php echo $row['state'];?></td>
                                            <td><?php echo $row['LGA'];?></td>
                                            <td><?php echo $row['purpose'];?></td>
                                            <td><?php echo $row['Med_Fit'];?></td>
					                                 
                                     
                                            
    
                                        </tr>

<?php    
          }
          }
?>


                                     </tbody>
                                </table>
                            </div>
                        </div>
                      </div>
<div id="editor"></div>
<button id="cmd">Generate PDF</button>

      </div>
      </div>
      </div> 
      </div>
      </div>
      </div>
      </div>
      </section>
      </div>
      </div>       
     
     
     
     
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
     
<script>
$(document).ready(function() {
    var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
       
    } );
    

    table.buttons().container()
        .insertBefore( '#example_filter' );

        table.buttons().container()
        .appendTo( '#example_wrapper .col-sm-12:eq(0)' );
} );



</script>

            
            
            
            
            
            
            
          </div>




            </div>
          </div>
        </section>
      </div>
