<?php
//ini_set('display_errors', 1);
//echo '1111';
session_start();
include_once('classFunctionStorage.php');
header("Access-Control-Allow-Origin: *");

//session_start(); // Starting Session
$error=''; // Variable To Store Error Message
$msg='';

//print_r($retuenedArray);
if (isset($_POST['submit'])) {
   if (empty($_POST['e_mail']) || empty($_POST['password'])) {
   $error = "e_mail or Password is invalid";
   } else {
   $newDBrequest = new dbConnSetClass;

   $retuenedArray = $newDBrequest -> siteLogin($_POST['e_mail'], $_POST['password']);
   }
}
?>