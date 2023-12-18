<?php

ini_set("display_errors", "1");
 
$name=$_POST['name'];
$email=$_POST['email'];
$country=$_POST['country'];
$message=$_POST['message'];
$to = "krishna.padam.it@gmail.com";
 $subject = "Contact Details" ;
 $body = "<div> <p>Name: ".$name."</p></br> <p>Email: ".$email."</p></br> <p>Country: ".$country."</p></br> <p>Message: ".$message."</p></br></div>";

    $headers = 'From: krishna.padam.it@gmail.com' . "\r\n" ;
    $headers .='Reply-To: '. $to . "\r\n" ;
    $headers .='X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";   
if(mail($to, $subject, $body,$headers)) {
  echo('<br>'."Email Sent ;D ".'</br>');
  } 
  else 
  {
  echo("<p>Email Message delivery failed...</p>");
  }


?>
