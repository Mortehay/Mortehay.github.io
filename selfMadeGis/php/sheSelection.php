<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['selectedCity']) {$selectedCity= $_POST['selectedCity'];} else {$selectedCity = $_REQUEST["selectedCity"];} 
$newDBrequest = new dbConnSetClass;
$query = "SELECT DISTINCT cubic_pgs_addr FROM ".$selectedCity.".".$selectedCity."_ctv_topology order by cubic_pgs_addr;";
//echo $query;
$queryArrayKeys = array('cubic_pgs_addr');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'she' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_pgs_addr']
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
?>

