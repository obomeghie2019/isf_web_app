<!-- <?php
  $num = readline("Enter the number: ");
  echo "Multiplication table of ",$num," is\n\n";     
  for ($i = 1; $i <= 10; $i++) {
    echo "\t", $num, " x ", $i, " = ", $num * $i, "\n";
  }
  
?> -->

<!DOCTYPE html>
<html>
  
<body>
    <center>         <h1 >
            Olowokere Sunny  : PHP Multiplication Table
        </h1>
  
  
        <form method="POST">
            Enter A Value To Generate  Multiplication Table:  
            <input type="text" name="number">
              
            <input type="Submit" 
                value="Draw Multiplication Table">
        </form>
    </center>
</body>
  
<div align="center">
  <table border="2" cellpadding="1" cellspacing="2">
    <tr>
      <td>
<?php 
if($_POST) { 
    $num = $_POST["number"]; 
       echo nl2br("<p >
        Multiplication Table of $num: </p>
    "); 
          
    for ($i = 1; $i <= 12; $i++) {          echo ("<p >$num"
            . " X " . "$i" . " = " 
            . $num * $i . "</p>
        "); 
    } 
} 
?>
</tr>
</td>
</table>
</div>
</html>