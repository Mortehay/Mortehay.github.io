<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
//if ($_POST['selectedCity']) {$selectedCity= $_POST['selectedCity'];} else {$selectedCity = $_REQUEST["selectedCity"];} 
$newDBrequest = new dbConnSetClass;
$query = "SELECT  e_mail, login_time FROM public.login ORDER BY login_time;";
//echo $query;
$queryArrayKeys = array('e_mail', 'login_time');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'e_mail' => $sumObjectsArray[$sumObjectsArrayKey]['e_mail'],
    'login_time' => substr($sumObjectsArray[$sumObjectsArrayKey]['login_time'], 0, 10)
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);  
?>

