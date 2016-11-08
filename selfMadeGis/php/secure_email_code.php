<?php
ini_set('display_errors', 1);
if(isset($_POST["mail"])){
	// Checking For Blank Fields..
	if($_POST["vname"]==""||$_POST["vemail"]==""||$_POST["sub"]==""||$_POST["msg"]==""){
		$mailmsg = "Fill All Fields..";
		header("location: ../index.php?msg=$mailmsg");
	}else{
		// Check if the "Sender's Email" input field is filled out
		$email2=$_POST['vemail'];
		// Sanitize E-mail Address
		$email2 =filter_var($email2, FILTER_SANITIZE_EMAIL);
		// Validate E-mail Address
		$email2= filter_var($email2, FILTER_VALIDATE_EMAIL);
		if (!$email2){
			$mailmsg = "Invalid Sender's Email";
			header("location: ../index.php?msg=$mailmsg");
		}
		else{
			$subject = $_POST['sub'];
			$message = $_POST['msg'];
			$headers = 'From:'. $email2 . "\r\n"; // Sender's Email
			$headers .= 'Cc:'. $email2 . "\r\n"; // Carbon copy to Sender
			// Message lines should not exceed 70 characters (PHP rule), so wrap it
			$message = wordwrap($message, 70);
			// Send Mail By PHP Mail Function
			mail("yurii.shpylovyi@volia.com", $subject, $message, $headers);
			#mail("oleksandr.sadovnik@volia.com", $subject, $message, $headers);
			$mailmsg = "Your mail has been sent successfuly ! Thank you for your feedback";
			header("location: ../index.php?msg=$mailmsg");


		}
	}
}
?>