<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['city']) {
  $selectedCity= $_POST['city'];
} else {
  $selectedCity = $_REQUEST['city'];
}
$newDBrequest = new dbConnSetClass;
$query = "SELECT DISTINCT cubic_street FROM ".$selectedCity.".".$selectedCity."_buildings WHERE cubic_street IS NOT NULL";
//echo $query;
$queryArrayKeys = array('cubic_street');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'cubic_street' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_street']
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
?>

