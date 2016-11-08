<?php
//ini_set('display_errors', 1);
session_start();
include('php/login.php'); // Includes Login Script

/*if(isset($_SESSION['login_user'])){
header("location: php/profile.php");
}
*/
$msg = NULL; 
$mailmsg = NULL;
 $msg = $_GET["msg"];
  if ($msg != NULL) {
  $msg=json_encode($msg);
  echo "<script type='text/javascript'>alert(".$msg.");</script>";
  }
  


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <title>login form Volia QGIS</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    
    <!-- Tims styles on top of blueprint -->
    <link rel="stylesheet" href="css/login.css" type="text/css" > 
    <link rel="stylesheet" href="css/feedback.css" type="text/css" > 
    <!-- jquery -->
    <script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
   
   
  </head>
  <body>

  <div class="wraper">
    <div class="request" id="request">

    <!-- Heading Of The Form -->
    <div class="head">
    <h3>FeedBack Form</h3>
    <p>This is feedback form. Send us your feedback !</p>
    </div>
    <!-- Feedback Form -->
    <form id="mail" action="php/secure_email_code.php" method="post">

    <input name="vname" placeholder="Your Name" type="text" value="" autocomplete="off">
    <input name="vemail" placeholder="Your Email" type="text" value="" autocomplete="off">
    <input name="sub" placeholder="Subject" type="text" value="" autocomplete="off">
    <h2>Your Suggestion/Feedback</h2>
    <textarea name="msg" placeholder="Type your text here..."></textarea>
    <input id="send" name="mail" type="submit" value="Send Feedback">
    <input id="return" type="button" value="return">
    </form>
  <?php 
    include('php/secure_email_code.php');
    $mailmsg = $_GET["mailmsg"];
    if ($mailmsg != NULL) {
    $mailmsg=json_encode($mailmsg);
     echo "<script type='text/javascript'>alert(".$mailmsg.");</script>";
     }
  ?>

  </div>
  <form id='login' action='php/login.php' method='post' accept-charset='UTF-8'>


    <div class="container">
      <label><b>E-mail</b></label>
      <input type="text" id="e_mail" placeholder="Enter e-mail" name="e_mail" required autocomplete="off">

      <label><b>Password</b></label>
      <input type="password" id="password" placeholder="Enter Password" name="password" required autocomplete="off">

      <button type="submit" name="submit">Login</button>
     
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <span><a href='#' id='visible_maker'>mail</a></span>
    
    </div>
  </form>
  </div>
  <?php
   
  ?>
    


   <script type="text/javascript" src="js/visible_maker.js"></script>
   <script type="text/javascript" src="js/login.js"></script>
     
  </body>
</html>
