<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
$emailer = new mailSender;
$feedbacker = new dbConnSetClass;
if ($_POST['e_mail']) {$email2= $_POST['e_mail'];} else {$email2 = $_REQUEST["e_mail"];}
if ($_POST['restriction']) {$restriction= $_POST['restriction'];} else {$restriction = $_REQUEST["restriction"];}
if ($_POST['sub']) {$sub= $_POST['sub'];} else {$sub = $_REQUEST["sub"];}
if ($_POST['request']) {$request= $_POST['request'];} else {$request = $_REQUEST["request"];}
//if($_POST['e_mail'] && $_POST['restriction'] && $_POST['sub'] && $_POST['request']){
if($email2 && $restriction) {
	// Sanitize E-mail Address
	$email2 =filter_var($email2, FILTER_SANITIZE_EMAIL);
	// Validate E-mail Address
	$email2= filter_var($email2, FILTER_VALIDATE_EMAIL);
	if (!$email2){
		$mailmsg = "Invalid Sender's Email";
		header("location: main_page.php?restriction=".$restriction."&e_mail=".$email2);
	}
	else{
		$subject = $sub;//$_POST['sub'];
		$message = $request;//$_POST['msg'];
		$headers = 'From:'. $email2 . "\r\n"; // Sender's Email
		$headers .= 'Cc:'. $email2 . "\r\n"; // Carbon copy to Sender
		// Message lines should not exceed 70 characters (PHP rule), so wrap it
		$message = wordwrap($message, 240);
		// log feedback to publick.gisfeedback
		$query ="SELECT 1 as counter FROM public.gisfeedback where feedback_text = '".$message."' AND feedback_email = '".$email2."';";
		$queryArrayKeys = array('counter');
		$retuenedArray = $feedbacker -> dbConnect($query, $queryArrayKeys, true);
		//print_r(count($retuenedArray[0]));

		if (empty($retuenedArray) && (strlen($message) > 0)){
			$query = "INSERT INTO public.gisfeedback(feedback_email, feedback_sub, feedback_text, feedback_time) select '".$email2."','".$sub."','".$message."', now() WHERE NOT EXISTS(SELECT 1 as counter FROM public.gisfeedback where feedback_text = '".$message."' AND feedback_email = '".$email2."');";
			$queryArrayKeys = false;
			$retuenedArray = $feedbacker -> dbConnect($query, $queryArrayKeys, true);
			//echo $query;
			$message .=$query;
			// Send Mail By PHP Mail Function
			$emailer ->mail_attachment('yurii.shpylovyi@volia.com','gisFeedBack',$subject , $message, '', ''); //( $to, $from,$subject , $message, $path, $filename)
			$mailmsg = "Your mail has been sent successfuly ! Thank you for your feedback";
			//echo $mailmsg;
		}
		if($restriction =='admin'){
			$query ="SELECT feedback_email, feedback_sub, feedback_text, feedback_status, feedback_time::timestamp(0), feedback_resolve_time::timestamp(0) FROM public.gisfeedback;";
		} else {
			$query ="SELECT feedback_email, feedback_sub, feedback_text, feedback_status, feedback_time::timestamp(0), feedback_resolve_time::timestamp(0) FROM public.gisfeedback where feedback_email = '".$email2."';";
		}
		
		$queryArrayKeys = array('feedback_email', 'feedback_sub', 'feedback_text', 'feedback_status', 'feedback_time', 'feedback_resolve_time');
		$retuenedArray = $feedbacker -> dbConnect($query, $queryArrayKeys, true);
		$sumObjectsArray = $retuenedArray;
		//print_r($sumObjectsArray);
		$arr_response = array('response' => array());
		foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
		   $arr = array(
		    'feedback_email' => $sumObjectsArray[$sumObjectsArrayKey]['feedback_email'],
		    'feedback_sub' => $sumObjectsArray[$sumObjectsArrayKey]['feedback_sub'],
		    'feedback_text' => $sumObjectsArray[$sumObjectsArrayKey]['feedback_text'],
		    'feedback_status' => $sumObjectsArray[$sumObjectsArrayKey]['feedback_status'],
		    'feedback_time' => $sumObjectsArray[$sumObjectsArrayKey]['feedback_time'],
		    'feedback_resolve_time' => $sumObjectsArray[$sumObjectsArrayKey]['feedback_resolve_time']
		    //'feedback_time' => date_format(date_create($sumObjectsArray[$sumObjectsArrayKey]['feedback_time']), "Y-m-d H:i:s"),
		    //'feedback_resolve_time' => date_format(date_create($sumObjectsArray[$sumObjectsArrayKey]['feedback_resolve_time']), "Y-m-d H:i:s")
		  );
		  array_push($arr_response['response'], $arr ); 
		}
		print json_encode($arr_response);
	}
}


?>