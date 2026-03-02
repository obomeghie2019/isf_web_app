<?php
session_start();

require_once "config.php";
#require_once "../../config/config.php";
$msg = "";


$email_err = $password_err= "";
$email = $password= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
  if (empty($_POST["email"])) {
    $email_err = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    
// check if e-mail address is well-formed
    
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Invalid email format"; 
    }
  }
  
  
   if (empty($_POST["password"])) {
    $password_err = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
    
// check if name only contains letters and whitespace
   
  }
    
        
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    
    
if($email == "" || $password == ""){
        $msg = "Email or Password fields cannot be empty!";
        
    }else {
        
                    
    
if($sql = "SELECT * FROM adminlogin WHERE email='$email'  AND password='$password'"){

                 $result = $conn->query($sql);
                 
if(mysqli_num_rows($result) > 0){
                     $row = mysqli_fetch_array($result);

                 $_SESSION['email']=$_POST['email'];
                   $_SESSION['password']=$row['password'];
                    $_SESSION['uid']=$row['id'];
                  

                    

header("location:../pages/home.php");
                
                
            }else{
                
                $msg = "Email or Password incorrect!";
            }
            
        
             
        }else{
            $msg = "Email or Password incorrect!";
        }
        
    }
}
    
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

 
 ?>