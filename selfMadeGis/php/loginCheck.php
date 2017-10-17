   <?php
   //session_start(); //session_start should be in an application wide global file

   //this code should only be in pages where you want to have login enabled
   if(!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
      header("location: ../index.php?msg=$msg");
      exit();
   }
   ?>