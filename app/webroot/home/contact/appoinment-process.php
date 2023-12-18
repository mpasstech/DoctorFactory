<?php

// define("WEBMASTER_EMAIL", 'themesflat@gmail.com');
//$address = "example@themeforest.net";
$address = "themesflat@gmail.com";
if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

$error = false;
$fields = array( 'fullname', 'email', 'telephone', 'doctor', 'date', 'time', 'message' );

foreach ( $fields as $field ) {
	if ( empty($_POST[$field]) || trim($_POST[$field]) == '' )
		$error = true;
}

if ( !$error ) {

	$name = stripslashes($_POST['fullname']);
	$email = trim($_POST['email']);
	$telephone = stripslashes($_POST['telephone']);	
	$doctor = stripslashes($_POST['doctor']);	
	$date = stripslashes($_POST['date']);	
	$time = stripslashes($_POST['time']);	
	$message = stripslashes($_POST['message']);

	// $mail = @mail(WEBMASTER_EMAIL, "You have a new message.", $message,
	// 	 "From: " . $name . " <" . $email . ">\r\n"
	// 	."Reply-To: " . $email . "\r\n"
	// 	."X-Mailer: PHP/" . phpversion());

	// if ( $mail ) {
	// 	echo 'Success';
	// } else {
	// 	echo $error;
	// }

	$e_subject = 'You\'ve been contacted by ' . $name . '.';


	// Configuration option.
	// You can change this if you feel that you need to.
	// Developers, you may wish to add more fields to the form, in which case you must be sure to add them here.

	$e_body = "You have been contacted by: $name" . PHP_EOL . PHP_EOL;
	$e_reply = "E-mail: $email\r\nPhone: $telephone" . PHP_EOL . PHP_EOL;
	$e_doctor = "\r\nRestoruan: $doctor";
	$e_date = "\r\nDate: $date" . PHP_EOL . PHP_EOL;
	$e_time = " \r\nTime: $time" ;	
	$e_content = "Message:\r\n$message" . PHP_EOL . PHP_EOL;

	$msg = wordwrap( $e_body . $e_content . $e_reply .$e_doctor . $e_date . $e_time, 70 );

	$headers = "From: $email" . PHP_EOL;
	$headers .= "Reply-To: $email" . PHP_EOL;
	$headers .= "MIME-Version: 1.0" . PHP_EOL;
	$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
	$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

	if(mail($address, $e_subject, $msg, $headers)) {

		// Email has sent successfully, echo a success page.

		echo 'Success';

	} else {

		echo 'ERROR!';

	}

}

?>