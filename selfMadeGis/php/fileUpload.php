<?php
//include('restriction.php'); 
include('classFunctionStorage.php');

///////////////////////////////////////
session_start();
$restriction = $_SESSION['restriction'];
$login_user = $_SESSION['e_mail']; 
ini_set('display_errors', 1);

$fileLoader = new fileUpload;
$fileLoader->upload($restriction,$login_user,'file_upload');

?>