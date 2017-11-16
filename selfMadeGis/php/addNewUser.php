<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
//echo $_REQUEST['buttonId'];
session_start();
$restriction = $_SESSION['restriction'];
$e_mail = $_SESSION['e_mail'];
if ($restriction === 'admin')  {
  $newDBrequest = new dbConnSetClass;
  if ($_REQUEST['buttonId'] =='addNewUser') {
    $arr_response = array('response'=>array());
    $e_mail = $_REQUEST['Email'];
    $restriction = $_REQUEST['Restriction'];
    $pass = $_REQUEST['Password'];
    $md5 =md5($pass);
    $map_links = to_pg_array($_REQUEST['map_links']);
    $file_links = to_pg_array($_REQUEST['file_links']);
    //$query = "INSERT INTO public.access(e_mail,restriction,password,md5,map_links) SELECT '".$e_mail."', '".$restriction."','".$pass."', '".$md5."','".'{"Повна карта міста","Карта покриття міста","Карта heatmap міста","Карта лінійно-кабельного обліку міста"}'."' WHERE NOT EXISTS(SELECT e_mail FROM public.access WHERE e_mail='".$e_mail."') ;";
    $query = "INSERT INTO public.access(e_mail,restriction,password,md5,map_links,file_links) SELECT '".$e_mail."', '".$restriction."','".$pass."', '".$md5."','".$map_links."','".$file_links."' WHERE NOT EXISTS(SELECT e_mail FROM public.access WHERE e_mail='".$e_mail."') ;";

    $conn = $newDBrequest -> setProp('dbConnSet', $connLSetings);
    $newDBrequest -> dbConnect($query, false, true);
    //echo $query;
    //array_push($arr_response['response'], $query ); 
  } elseif ($_REQUEST['buttonId'] =='deleteUser') {
    $e_mail = $_REQUEST['Email'];
    $query = "DELETE FROM public.access where e_mail='".$e_mail."';";
    $conn = $newDBrequest -> setProp('dbConnSet', $connLSetings);
    $newDBrequest -> dbConnect($query, false, true);
  }
  
}

?>