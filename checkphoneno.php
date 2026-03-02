<?php

require ("db_params.php");

if(isset($_POST['phonesubmit'])){

    $tid = $_POST['tid'];




    if($tid == ""){

    echo "<script>alert('Please Valid Phone Number')</script>";

    }else{


        $sql= "SELECT * FROM studentreg WHERE phone = '$tid'";
        $result = mysqli_query($link,$sql);
        if(mysqli_num_rows($result) > 0){
          $row = mysqli_fetch_assoc($result);

          $pid = $row['phone'];
          if(isset($row['phone'])){

              $pid = $row['phone'];



    if($tid == $pid){



    header("Location:ppprint.php?phonenumber=$tid");

    }

    else{

        echo "<script>alert('No Record Found For the Phone Number')</script>";
    }

}

}

}
}

?>