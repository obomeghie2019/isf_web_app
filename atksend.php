<?php
require 'db_params.php';
$code_id = $_SESSION['SESS_CODE_NAME'];
$d = date('D d M Y');
$t = date('h : i : sA');


if(isset($_POST['regsubmit'])){
    $cod = $_POST['code'];
    $athelete = $_POST['atname'];
    $fon = $_POST['phoneno'];
    $email = $_POST['email'];
    $stat = $_POST['state'];
    $localg = $_POST['lga'];
    $ses = $_POST['gender'];
    $appno = $_POST['appno'];
    $date = $d .' '.$t;
    $time = "inactive";
    $status = "undone";

        $query = "INSERT INTO studentreg (surname, othernames, gender, religion, bday, state, LGA, nationality, email, phone, address, school, faculty, course, level, jamb, sub1, grade1, sub2, grade2, sub3, grade3, sub4, grade4, total, appno, date, time, status, examdate, photo) VALUES ('$surname', '$othernames', '$gender', '$religion', '$date', '$state', '$local', '$nationality', '$email', '$phone', '$address', '$school', '$faculty', '$course', '$level', '$jamb', '$sub1', '$grade1', '$sub2', '$grade2', '$sub3', '$grade3', '$sub4', '$grade4', '$total', '$appno', '$date', '$time', '$status', '$examdate', '$filePath')";

        $sqli = mysql_query ("UPDATE regcode SET status = 'used' WHERE code = '$code_id'");

        if ($query){
           header("Location:re-print.php.php");

       }

       mysql_query($query) or die ('Error, query failed');

       mysql_close();
   }


?>