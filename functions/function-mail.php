<?php
	require 'lib/PHPMailer/src/Exception.php';
	require 'lib/PHPMailer/src/PHPMailer.php';
	require 'lib/PHPMailer/src/SMTP.php';

	function sendMail($emailAddress, $name, $subject, $message){
		// $emailAddress = 'jairo.sanchez943.js@gmail.com';
		// $name = 'Jairo Sanchez';
		// $subject = 'Applying as Handyman';
		// $message = 'Please pass one of the following requirements';
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);                              // Passing `true` enables exceptions
		try {
			
		    //Server settings
		    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = 'findandhirehandyman@gmail.com';                 // SMTP username
		    $mail->Password = 'findhirehandyman';                           // SMTP password
		    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = 587;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('findandhirehandyman@gmail.com', 'Find & Hire');
		    $mail->addAddress($emailAddress, $name);               // Name is optional
		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $message;

		    $mail->send();
		    //echo '<script>alert("Message has been sent");</script>';

		} catch (Exception $e) {
		    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}
?>