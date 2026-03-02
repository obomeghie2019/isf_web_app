<?php
// Include config file
include "../../config/db.php";
include "../../config/config.php";




$msg = "";



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


 
// Define variables and initialize with empty values
$username = $email = $password = $cpassword = $address = $phone = $country = "";
$username_err = $email_err = $password_err = $cpassword_err =  $address_err = $phone_err = $country_err =  "";



if(isset($_GET['refcode'])){

    $refcode = $_GET['refcode'];

}else {
    $refcode='';
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(isset($_POST['terms'])){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }


      // Validate email
      if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["cpassword"]))){
        $cpassword_err = "Please confirm password.";     
    } else{
        $cpassword = trim($_POST["cpassword"]);
        if(empty($password_err) && ($password != $cpassword)){
            $cpassword_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($cpassword_err)){
        
        
         $phone= $_POST['phone'];
          $country = $_POST['country'];
           $address = $_POST['address'];
        
            $referred = $_POST['referred'];
            $bonus = $_POST['bonus'];
        $token ='kllcabcdg19etsfjhdshdsh35678gwyjerehuhb/>()[]{}|\dTSGSAWQUJHDCSMNBVCBNRTPZXMCBVN1234567890';
            $token = str_shuffle($token);
             $token= substr($token,0, 10);



             $refcode ='kllcabcdg19etsfjhdshdsh35678gwyjerehuhbdTSGSAWQUJHDCSMNBVCBNRTPZXMCBVN1234567890';
                $refcode = str_shuffle($refcode);
                 $refcode= substr($refcode,0, 10);
        // Prepare an insert statement
        

$sql = "INSERT INTO users (username, email, password, token, refcode, referred, walletbalance, phone, address, country ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        
if($stmt = mysqli_prepare($link, $sql)){
            
// Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssss", $param_username, $param_email, $param_password, $param_token, $param_refcode, $param_referred, $param_bonus, $param_phone, $param_address, $param_country );
            
            
// Set parameters
            
$param_username = $username;
            
$param_email = $email;
            
$param_password = $password;
             
$param_token = $token;
            
$param_refcode = $refcode;
            
$param_referred = $referred;
            
$param_bonus = $bonus;
            
$param_phone  = $phone;
            
$param_address = $address;
            
$param_country = $country;
            
            
            

// Attempt to execute the prepared statement
            
if(mysqli_stmt_execute($stmt)){
              

require_once "PHPMailer/PHPMailer.php";
require_once 'PHPMailer/Exception.php';


//PHPMailer Object
$mail = new PHPMailer;

    
//From email address and name
        
$mail->setFrom($emaila);
   
$mail->FromName = $name;
              
 

$mail->addAddress("$email"); 
//Recipient name is optional

//Address to which recipient will reply


//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Email Verification";
$mail->Body = '
              
               
<div style="background: #f5f7f8;width: 100%;height: 100%; font-family: sans-serif; font-weight: 100;" class="be_container"> 


<div style="background:#fff;max-width: 600px;margin: 0px auto;padding: 30px;"class="be_inner_containr"> <div class="be_header">



<div class="be_logo" style="float: left;"> 
<img src="https://<?php echo $bankurl;?>/admin/c2wad/logo/<?php echo $logo;?>" alt="navbar brand" 
					
style="height:40px;width:100px; margin-top:15px;"> 
</div>

<div class="be_user" style="float: right"> <p>Dear: '.$username.'</p> </div> 

<div style="clear: both;">

</div> 

<div class="be_bluebar" style="background: #1976d2; padding: 20px; color: #fff;margin-top: 10px;">

<h1>Thank you for Registering on  '.$name.'</h1>

</div> </div> 

<div class="be_body" style="padding: 20px;"> <p style="line-height: 25px;"> 
You will receive a notification once your account has been activated!</p>
</br>

        </p>

<div class="be_footer">
<div style="border-bottom: 1px solid #ccc;"></div>


<div class="be_bluebar" style="background: #1976d2; padding: 20px; color: #fff;margin-top: 10px;">

<p> Please do not reply to this email. Emails sent to this address will not be answered. 
Copyright ©2021 '.$name.'. </p> <div class="be_logo" style=" width:60px;height:40px;float: right;"> </div> </div> </div> </div></div>      
              


';


if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    


$msg =  "Registration Competed!";}
               
            

} else{
                

$msg = "Something went wrong. Please try again later.";
            }
        }
         
        

// Close statement
        mysqli_stmt_close($stmt);
    }
    
    

// Close connection
    mysqli_close($link);
}else{
    
     


$msg =  "You have to accept terms and condition!";
}
}

?> 

<div id="google_translate_element" style="margin-left:1%">

</div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!DOCTYPE html>
<html lang="zxx">
<head>
<title>TMD Coin-Capital</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/snow.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/component.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style_grid.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- font-awesome-icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome-icons -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">





<style>
body {
  background-image: url("../../config/888bg.jpg");
  background-repeat: no-repeat;
  background-attachment: fixed; 
  background-size: 100% 100%;
}
</style>


</head>
<style>
#inp{
   border-radius:5px; 
    background-color:#fff;
}    
 #den{
        border-radius:5px; 
     background-color:#8ca2bf;
     color:green;
     margin-top:100px;
 } 

</style>

<body>
		

						<div class="">
							<!-- /signup -->
							   <div class="">
								<div class="registration">
								
												<div id="den" class="signin-form profile"  style="margin-top:50px;background-color:#220a80">
												    
												    <img src="https://<?php echo $bankurl;?>/admin/c2wad/logo/<?php echo $logo;?>" alt="navbar brand" 
					style="height:40px;width:100px; margin-top:15px;">
												    
													<h2 style="color:#fff">Member Sign up Form</h2>

													<?php if($msg != "") echo "<div style='padding:20px;background-color:#dce8f7;color:black'> $msg</div class='btn btn-success'>" ."</br></br>";  ?>

													<div class="login-form">
														<form  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">

                                                       
														<input type="hidden" name="bonus"   value="<?php echo $rb;?>">
															<input id="inp" type="text" name="username" placeholder="Username"  value="<?php echo $username; ?>">
                                                            <span class="help-block"><?php echo $username_err; ?></span>

															
																<input id="inp" type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>">
                                                                <span class="help-block"><?php echo $email_err; ?></span>
                                                               
															   
																<input id="inp" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
                                                                <span class="help-block"><?php echo $password_err; ?></span>

																
																<input id="inp" type="password" name="cpassword" placeholder="Confirm Password" value="<?php echo $cpassword; ?>">
																
																
																 <input id="inp"  placeholder="Phone Number" type="text" name="phone"   value="<?php echo $phone; ?>">
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                  <select style="border-radius:5px; background-color:#fff; padding:10px; width:100%" name="country">
      <option value="AF">Select Country</option>
      <option value="Afghanistan">Afghanistan</option>
 <option value="Åland Islands">Åland Islands</option>
 <option value="Albania">Albania</option>
 <option value="Algeria">Algeria</option>
 <option value="American Samoa">American Samoa</option>
 <option value="Andorra">Andorra</option>
 <option value="Angola">Angola</option>
 <option value="Anguilla">Anguilla</option>
 <option value="Antarctica">Antarctica</option>
 <option value="Antigua and Barbuda">Antigua and Barbuda</option>
 <option value="Argentina">Argentina</option>
 <option value="Armenia">Armenia</option>
 <option value="Aruba">Aruba</option>
 <option value="Australia">Australia</option>
 <option value="Austria">Austria</option>
 <option value="Azerbaijan">Azerbaijan</option>
 <option value="Bahamas">Bahamas</option>
 <option value="Bahrain">Bahrain</option>
 <option value="Bangladesh">Bangladesh</option>
 <option value="Barbados">Barbados</option>
 <option value="Belarus">Belarus</option>
 <option value="Belgium">Belgium</option>
 <option value="Belize">Belize</option>
 <option value="Benin">Benin</option>
 <option value="Bermuda">Bermuda</option>
 <option value="Bhutan">Bhutan</option>
 <option value="Bolivia">Bolivia, Plurinational State of</option>
 <option value="Bonaire">Bonaire, Sint Eustatius and Saba</option>
 <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
 <option value="Botswana">Botswana</option>
 <option value="Bouvet Island">Bouvet Island</option>
 <option value="Brazil">Brazil</option>
 <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
 <option value="Brunei">Brunei Darussalam</option>
 <option value="Bulgaria">Bulgaria</option>
 <option value="Burkina Faso">Burkina Faso</option>
 <option value="Burundi">Burundi</option>
 <option value="Cambodia">Cambodia</option>
 <option value="Cameroon">Cameroon</option>
 <option value="Canada">Canada</option>
 <option value="Cape Verde">Cape Verde</option>
 <option value="Cayman Islands">Cayman Islands</option>
 <option value="Central African Republic">Central African Republic</option>
 <option value="Chad">Chad</option>
 <option value="Chile">Chile</option>
 <option value="China">China</option>
 <option value="Christmas Island">Christmas Island</option>
 <option value="Cocos">Cocos (Keeling) Islands</option>
 <option value="Colombia">Colombia</option>
 <option value="Comoros">Comoros</option>
 <option value="Congo">Congo</option>
 <option value="Congo, the Democratic Republic">Congo, the Democratic Republic of the</option>
 <option value="Cook Islands">Cook Islands</option>
 <option value="Costa Rica">Costa Rica</option>
 <option value="Côte d'Ivoire">Côte d'Ivoire</option>
 <option value="Croatia">Croatia</option>
 <option value="Cuba">Cuba</option>
 <option value="Curaçao">Curaçao</option>
 <option value="Cyprus">Cyprus</option>
 <option value="Czech Republic">Czech Republic</option>
 <option value="Denmark">Denmark</option>
 <option value="Djibouti">Djibouti</option>
 <option value="Dominica">Dominica</option>
 <option value="Dominican Republic">Dominican Republic</option>
 <option value="Ecuador">Ecuador</option>
 <option value="Egypt">Egypt</option>
 <option value="El Salvador">El Salvador</option>
 <option value="Equatorial Guinea">Equatorial Guinea</option>
 <option value="Eritrea">Eritrea</option>
 <option value="Estonia">Estonia</option>
 <option value="Ethiopia">Ethiopia</option>
 <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
 <option value="Faroe Islands">Faroe Islands</option>
 <option value="Fiji">Fiji</option>
 <option value="Finland">Finland</option>
 <option value="France">France</option>
 <option value="French Guiana">French Guiana</option>
 <option value="French Polynesia">French Polynesia</option>
 <option value="French Southern Territories">French Southern Territories</option>
 <option value="Gabon">Gabon</option>
 <option value="Gambia">Gambia</option>
 <option value="Georgia">Georgia</option>
 <option value="Germany">Germany</option>
 <option value="Ghana">Ghana</option>
 <option value="Gibraltar">Gibraltar</option>
 <option value="Greece">Greece</option>
 <option value="Greenland">Greenland</option>
 <option value="Grenada">Grenada</option>
 <option value="Guadeloupe">Guadeloupe</option>
 <option value="Guam">Guam</option>
 <option value="Guatemala">Guatemala</option>
 <option value="Guernsey">Guernsey</option>
 <option value="Guinea">Guinea</option>
 <option value="Guinea-Bissau">Guinea-Bissau</option>
 <option value="Guyana">Guyana</option>
 <option value="Haiti">Haiti</option>
 <option value="Heard Island">Heard Island and McDonald Islands</option>
 <option value="Holy See">Holy See (Vatican City State)</option>
 <option value="Honduras">Honduras</option>
 <option value="Hong Kong">Hong Kong</option>
 <option value="Hungary">Hungary</option>
 <option value="Iceland">Iceland</option>
 <option value="India">India</option>
 <option value="Indonesia">Indonesia</option>
 <option value="Iran">Iran, Islamic Republic of</option>
 <option value="Iraq">Iraq</option>
 <option value="Ireland">Ireland</option>
 <option value="Isle of Man">Isle of Man</option>
 <option value="Israel">Israel</option>
 <option value="Italy">Italy</option>
 <option value="Jamaica">Jamaica</option>
 <option value="Japan">Japan</option>
 <option value="Jersey">Jersey</option>
 <option value="Jordan">Jordan</option>
 <option value="Kazakhstan">Kazakhstan</option>
 <option value="Kenya">Kenya</option>
 <option value="Kiribati">Kiribati</option>
 <option value="Korea, Democratic">Korea, Democratic People's Republic of</option>
 <option value="Korea, Republic">Korea, Republic of</option>
 <option value="Kuwait">Kuwait</option>
 <option value="Kyrgyzstan">Kyrgyzstan</option>
 <option value="Lao">Lao People's Democratic Republic</option>
 <option value="Latvia">Latvia</option>
 <option value="Lebanon">Lebanon</option>
 <option value="Lesotho">Lesotho</option>
 <option value="Liberia">Liberia</option>
 <option value="Libya">Libya</option>
 <option value="Liechtenstein">Liechtenstein</option>
 <option value="Lithuania">Lithuania</option>
 <option value="Luxembourg">Luxembourg</option>
 <option value="Macao">Macao</option>
 <option value="Macedonia">Macedonia, the former Yugoslav Republic of</option>
 <option value="Madagascar">Madagascar</option>
 <option value="Malawi">Malawi</option>
 <option value="Malaysia">Malaysia</option>
 <option value="Maldives">Maldives</option>
 <option value="Mali">Mali</option>
 <option value="Malta">Malta</option>
 <option value="Marshall Islands">Marshall Islands</option>
 <option value="Martinique">Martinique</option>
 <option value="Mauritania">Mauritania</option>
 <option value="Mauritius">Mauritius</option>
 <option value="Mayotte">Mayotte</option>
 <option value="Mexico">Mexico</option>
 <option value="Micronesia">Micronesia, Federated States of</option>
 <option value="Moldova">Moldova, Republic of</option>
 <option value="Monaco">Monaco</option>
 <option value="Mongolia">Mongolia</option>
 <option value="Montenegro">Montenegro</option>
 <option value="Montserrat">Montserrat</option>
 <option value="Morocco">Morocco</option>
 <option value="Mozambique">Mozambique</option>
 <option value="Myanmar">Myanmar</option>
 <option value="Namibia">Namibia</option>
 <option value="Nauru">Nauru</option>
 <option value="Nepal">Nepal</option>
 <option value="Netherlands">Netherlands</option>
 <option value="New Caledonia">New Caledonia</option>
 <option value="New Zealand">New Zealand</option>
 <option value="Nicaragua">Nicaragua</option>
 <option value="Niger">Niger</option>
 <option value="Nigeria">Nigeria</option>
 <option value="Niue">Niue</option>
 <option value="Norfolk">Norfolk Island</option>
 <option value="Mariana">Northern Mariana Islands</option>
 <option value="Norway">Norway</option>
 <option value="Oman">Oman</option>
 <option value="Pakistan">Pakistan</option>
 <option value="Palau">Palau</option>
 <option value="Palestinian">Palestinian Territory, Occupied</option>
 <option value="Panama">Panama</option>
 <option value="Papua">Papua New Guinea</option>
 <option value="Paraguay">Paraguay</option>
 <option value="Peru">Peru</option>
 <option value="Philippines">Philippines</option>
 <option value="Pitcairn">Pitcairn</option>
 <option value="Poland">Poland</option>
 <option value="Portugal">Portugal</option>
 <option value="Puerto Rico">Puerto Rico</option>
 <option value="Qatar">Qatar</option>
 <option value="Réunion">Réunion</option>
 <option value="Romania">Romania</option>
 <option value="Russian">Russian Federation</option>
 <option value="Rwanda">Rwanda</option>
 <option value="Saint Barthélemy">Saint Barthélemy</option>
 <option value="Saint Helena">Saint Helena, Ascension and Tristan da Cunha</option>
 <option value="Saint Kitts">Saint Kitts and Nevis</option>
 <option value="Saint Lucia">Saint Lucia</option>
 <option value="Saint Martin">Saint Martin (French part)</option>
 <option value="Saint Pierre">Saint Pierre and Miquelon</option>
 <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
 <option value="Samoa">Samoa</option>
 <option value="San Marino">San Marino</option>
 <option value="Sao Tome">Sao Tome and Principe</option>
 <option value="Saudi Arabia">Saudi Arabia</option>
 <option value="Senegal">Senegal</option>
 <option value="Serbia">Serbia</option>
 <option value="Seychelles">Seychelles</option>
 <option value="Sierra Leone">Sierra Leone</option>
 <option value="Singapore">Singapore</option>
 <option value="Sint Maarten">Sint Maarten (Dutch part)</option>
 <option value="Slovakia">Slovakia</option>
 <option value="Slovenia">Slovenia</option>
 <option value="Solomon Islands">Solomon Islands</option>
 <option value="Somalia">Somalia</option>
 <option value="South Africa">South Africa</option>
 <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
 <option value="South Sudan">South Sudan</option>
 <option value="Spain">Spain</option>
 <option value="Sri Lanka">Sri Lanka</option>
 <option value="Sudan">Sudan</option>
 <option value="Suriname">Suriname</option>
 <option value="Svalbard">Svalbard and Jan Mayen</option>
 <option value="Swaziland">Swaziland</option>
 <option value="Sweden">Sweden</option>
 <option value="Switzerland">Switzerland</option>
 <option value="Syrian">Syrian Arab Republic</option>
 <option value="Taiwan">Taiwan, Province of China</option>
 <option value="Tajikistan">Tajikistan</option>
 <option value="Tanzania">Tanzania, United Republic of</option>
 <option value="Thailand">Thailand</option>
 <option value="Timor">Timor-Leste</option>
 <option value="Togo">Togo</option>
 <option value="Tokelau">Tokelau</option>
 <option value="Tonga">Tonga</option>
 <option value="Trinidad and Tobago">Trinidad and Tobago</option>
 <option value="Tunisia">Tunisia</option>
 <option value="Turkey">Turkey</option>
 <option value="Turkmenistan">Turkmenistan</option>
 <option value="Turks">Turks and Caicos Islands</option>
 <option value="Tuvalu">Tuvalu</option>
 <option value="Uganda">Uganda</option>
 <option value="Ukraine">Ukraine</option>
 <option value="United Arab Emirates">United Arab Emirates</option>
 <option value="United Kingdom">United Kingdom</option>
 <option value="United States">United States</option>
 <option value="nited States Minor Outlying Islands">United States Minor Outlying Islands</option>
 <option value="Uruguay">Uruguay</option>
 <option value="Uzbekistan">Uzbekistan</option>
 <option value="Vanuatu">Vanuatu</option>
 <option value="Venezuela">Venezuela, Bolivarian Republic of</option>
 <option value="Viet Nam">Viet Nam</option>
 <option value="Virgin Islands, British">Virgin Islands, British</option>
 <option value="Virgin Islands, U.S">Virgin Islands, U.S.</option>
 <option value="Wallis and Futuna">Wallis and Futuna</option>
 <option value="Western Sahara">Western Sahara</option>
 <option value="Yemen">Yemen</option>
 <option value="Zambia">Zambia</option>
 <option value="Zimbabwe">Zimbabwe</option>
</select>

                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                 
                                                                  
                                                                   <input id="inp"  placeholder="Address" type="text" name="address"   value="<?php echo $address; ?>">
                                                                
                                                                <span class="help-block"><?php echo $cpassword_err; ?></span>

                                                                <input id="inp" type="hidden" name="referred"  readonly value="<?php echo $refcode; ?>">
                                                                
                                                                
                                                                
                                                                
                                                                <label>Accept terms</label>
                                                                 <input id="inp" type="checkbox"  name="terms"  value="" >
							</br>
															<div class="tp">
																<input  type="submit" name="submit" value="SIGN Up">
															</div>
														</form>
													</div>
													
													<p><a href="#" style="color:#fff"> By clicking Sign Up, I agree to your terms</a></p>
													 <h6 style="color:#fff">Already a member &nbsp; <a href="signin.php"> LOGIN</a><h6>
												</div>
										</div>
										<!--copy rights start here-->
											<div class="copyrights_agile two">
												 <p>© 2020 <?php echo $name;?>. All Rights Reserved</a> </p>
											</div>	
											 <!--copy rights end here-->
						    </div>
						</div>
						

          <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
		  
		
<script type="text/javascript" src="js/bootstrap-3.1.1.min.js"></script>


</body>
</html>