<?php
//ini_set('display_errors', 1);
//include('restriction.php');
if($_SERVER['SERVER_ADDR']) {
	$server_address = $_SERVER['SERVER_ADDR'].'site'; 
} else {
	$server_address = '10.112.129.170'.'server';
}
//$server_address = $_SERVER['SERVER_ADDR']; 
echo  $server_address;
?>