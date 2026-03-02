<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
include 'users_query.php';
  include 'header.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

/* ==============================
   Get Current User Securely
================================ */
$stmtUser = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$current_user = $stmtUser->fetch(PDO::FETCH_ASSOC);

/* ==============================
   Dashboard Stats (FAST COUNT)
================================ */
$countStmt = $conn->prepare("SELECT COUNT(*) FROM atheletes");
$countStmt->execute();
$totalu = $countStmt->fetchColumn();

/* ==============================
   Pagination Setup
================================ */
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

/* Total rows for pagination */
$totalRows = $totalu;
$totalPages = ceil($totalRows / $limit);
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
  
  
  
<script type="text/javascript" charset="utf8" src=""></script>
  <script type="text/javascript" charset="utf8" src=""></script>

 
  



    
    
  
                   


                               
    
    
    
    
    
    

   
   



<style>
.table-responsive {
overflow-x: hidden;
}
@media (max-width: 8000px) {
.table-responsive {
overflow-x: auto;
}
</style>
  </head>
  <body>

  <!-- Main Content -->
      <div class="main-content">

        <section class="section">

          <div class="section-header">
            <h1><i class="fa fa-users" style="font-size:30px"></i> Update Package</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="">ISF 2024</a></div>
                 <div class="breadcrumb-item"></div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 col-sx-12">
              
  
              
              
               
<div class="table-responsive">
                     
<table class="display"  id="example">
                                    
<thead>
                                        
<tr>
                                             
                        <th style="display:none">PHONE</th>
                                            

                                            <th>NAMES</th>
                                            <!--<th>EMAIL</th>-->
                                           <th>REG DATE </th>
                                             <th>PHONE NO</th>
                                            <th>GENDER</th>
                                             <th>STATE</th>
                                             <th>LGA</th>
                                              <th>PURPOSE</th>
                                             <th>FIT</th>
                                             <th>ACTION</th>
                                             
                                        </tr>
                                    </thead>
                                    <tbody>                                          
                                       
</tr>
                                    
</thead>
                                    
<tbody>


                                    
<?php
try {
    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM atheletes");
    $stmt->execute();

    // Fetch all rows
    $athletes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($athletes) {
        foreach ($athletes as $row) {
            // Check if id exists
            if (isset($row['id'])) {
                $ids   = $row['id'];
                $pname = $row['names'];
                $rdate = $row['date'];
                $phon  = $row['phone'];
                $ged   = $row['gender'];
                $sta   = $row['state'];
                $lg    = $row['LGA'];
                $pup   = $row['purpose'];
                $mfit  = $row['Med_Fit'];
            }
?>
            <!-- Your HTML table row -->
            <tr>
                <td><?= htmlspecialchars($pname) ?></td>
                <td><?= htmlspecialchars($rdate) ?></td>
                <td><?= htmlspecialchars($phon) ?></td>
                <td><?= htmlspecialchars($ged) ?></td>
                <td><?= htmlspecialchars($sta) ?></td>
                <td><?= htmlspecialchars($lg) ?></td>
                <td><?= htmlspecialchars($pup) ?></td>
                <td><?= htmlspecialchars($mfit) ?></td>
                <td>
                    <a href="card-edit.php?id=<?= $ids ?>">
                        <button class="btn btn-info"><i class="fas fa-edit"></i> Edit</button>
                    </a>
                </td>
            </tr>
<?php
        }
    }
} catch (PDOException $e) {
    echo "Error fetching athletes: " . $e->getMessage();
}
?>

				

                            
                                        
<tr class="odd gradeX">
                                      
<form action="card.php" method="POST">
                                          
                                         
<td style="display:none"> <input type="text" name="UID" value="<?php echo $row['id'];?>"></td>
                                            


                                            <td><?php echo $row['names'];?></td>
                                            <!--<td><?php echo $row['email'];?></td>-->
                                            <td><?php echo $row['date'];?></td>
                                            <td><?php echo $row['phone'];?></td>
                                            <td><?php echo $row['gender'];?></td>
                                            <td><?php echo $row['state'];?></td>
                                            <td><?php echo $row['LGA'];?></td>
                                            <td><?php echo $row['purpose'];?></td>
                                            <td><?php echo $row['Med_Fit'];?></td>
   
                                       </form>
                                            
 <td><a href="card-edit.php?id=<?php echo $ids;?>"><button  name="view" disabled style="width:100%" class="btn btn-info"><span class="fas fa-check">-Edit </span></button></a></td>

                                           
                                        
</tr>

<?php    
         
?>


                                     
</tbody>
                                
</table>
                            
</div>
                        
</div>
                      

      
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

  </body>
</html>