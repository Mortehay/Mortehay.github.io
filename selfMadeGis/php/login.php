<?php
session_start();
header("Access-Control-Allow-Origin: *");
//ini_set('display_errors', 1);
//session_start(); // Starting Session
$error=''; // Variable To Store Error Message
$msg='';
if (isset($_POST['submit'])) {
if (empty($_POST['e_mail']) || empty($_POST['password'])) {
$error = "e_mail or Password is invalid";
}
else
{
// Define $e_mail and $password
$e_mail=$_POST['e_mail'];
print $e_mail;
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
print $password;

   $host        = "host=127.0.0.1";
   $port        = "port=5432";
   $dbname      = "dbname=postgres";
   $credentials = "user=simpleuser password=simplepassword";

 $db = pg_connect( "$host $port $dbname $credentials"  );
   if(!$db){
      echo "Error : Unable to open database\n";
   } else {
      //echo "Opened database successfully\n";
   }
   $sql = "SELECT e_mail, password, restriction   FROM public.access WHERE password = '$password' AND e_mail= '$e_mail'";
   $ret = pg_query($db, $sql);
   if(!$ret){
      echo pg_last_error($db);
   } else {
      //echo "Records created successfully\n";
      $rows = pg_num_rows($ret);
      $row = pg_fetch_row($ret);
      echo $row;
      if ($rows == 1) {
         if ($row[2]!==NULL) {
            $_SESSION['login_user']=$e_mail; // Initializing Session
            $restriction=$row[2];
            
            $login_time = pg_query($db, "INSERT INTO public.login(e_mail, login_time) VALUES ('$e_mail',now())");
            //echo 'yes';
            
            header("location: main_page.php?restriction=$restriction&e_mail=$e_mail"); // Redirecting To Other Page
         }
         
      } else {
         
      $msg = "wrong e-mail or password";
      header("location: ../index.php?msg=$msg");

      }
   }

pg_close($db); // Closing Connection
}
}

?>