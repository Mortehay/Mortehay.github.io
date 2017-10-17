
<div class="request" id="request">

    <!-- Heading Of The Form -->
    <div class="head">
	    <h3>FeedBack Form</h3>
	    <p>This is feedback form. Send us your feedback !</p>
	</div>
	    <!-- Feedback Form -->
	
		<?php
			$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$url_query = explode('&',parse_url($actual_link)['query']);
			
				$e_mail= $_REQUEST['restriction'];


				$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				//print_r(explode('&',parse_url($actual_link)['query']));
				$user_param_array = array();
				
				foreach($url_query as $item){
					if (strpos($item, 'restriction=') !== false) {  $user_param_array['user_restriction'] = str_replace('restriction=','',$item);}
					if (strpos($item, 'e_mail=') !== false) {  $user_param_array['user_e_mail'] = str_replace('e_mail=','',$item);}
				}
				//print_r($user_param_array);
			if (count($user_param_array)>0) {
				echo '<div id="mail">';
				echo '<select name="sub">
					<option>питання по карті QGIS/веб-інтерфейсу</option>
					<option   selected>питання по роботі вебінтерфейсу/QGIS plugins</option>
					<option >запит на додавання/корегування нових/старих елементів</option>
				</select>';
				echo '<h2>Your Suggestion/Feedback</h2>';
	    		echo '<textarea name="msg" placeholder="Type your text here..."></textarea>';
	    		echo '<div class="buttonWrapper"><input id="sendFeedback" class="myToolButton" name="mail" type="button" value="Send Feedback"  data-restriction="'.$user_param_array['user_restriction'].'" data-e_mail="'.$user_param_array['user_e_mail'] .'"></div>';
	    		echo '<div class="buttonWrapper"><input id="return" type="button" class="myToolButton" value="return"></div>';
	    		echo '</div>';
			} else{
				echo '<form id="mail" action="php/secure_email_code.php" method="post">';
				echo '<input name="vname" placeholder="Your Name" type="text" value="" autocomplete="off">';
				echo '<input name="vemail" placeholder="Your Email" type="text" value="" autocomplete="off">';
				echo '<input name="sub" placeholder="Subject" type="text" value="" autocomplete="off">';
				echo '<h2>Your Suggestion/Feedback</h2>';
	    		echo '<textarea name="msg" placeholder="Type your text here..."></textarea>';
	    		echo '<input id="send" name="mail" type="submit" value="Send Feedback">';
	    		echo '<input id="return" type="button" value="return">';
    			echo '</form>';
			}
		?>

  <?php 
  if($_REQUEST['restriction'] and $_REQUEST['e_mail']){
  	include('secure_email_code.php?restriction='.$_REQUEST['restriction'] .'&e_mail='.$_REQUEST['e_mail']);
  } else{
  	include('secure_email_code.php');
  }
    
    $mailmsg = $_GET["mailmsg"];
    if ($mailmsg != NULL) {
    $mailmsg=json_encode($mailmsg);
     echo "<script type='text/javascript'>alert(".$mailmsg.");</script>";
     }
  ?>

  </div>
