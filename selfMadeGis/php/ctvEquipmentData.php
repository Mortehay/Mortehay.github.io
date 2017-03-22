<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['city']) {$selectedCity= $_POST['city'];} else {$selectedCity = $_REQUEST["city"];} 
if ($_POST['cubic_code']) {$cubic_code= $_POST['cubic_code'];} else {$cubic_code = $_REQUEST["cubic_code"];} 
$newDBrequest = new dbConnSetClass;
$query = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_ou_name, cubic_ou_street, cubic_ou_house,  cubic_coment, archive_link, link   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE  cubic_code ='".$cubic_code."';";
//echo $query;
$queryArrayKeys = array('cubic_city', 'cubic_street', 'cubic_house', 'cubic_name', 'cubic_code', 'cubic_ou_code', 'cubic_ou_name', 'cubic_ou_street', 'cubic_ou_house',  'cubic_coment', 'archive_link', 'link');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('equipment' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'cubic_city' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_city'],
    'cubic_coment' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_coment'],
    'cubic_name' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],
    'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    'cubic_street' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_street'],
    'cubic_house' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_house'],
    'cubic_ou_name' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_name'],
    'cubic_ou_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_code'],
    'cubic_ou_street' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_street'],
    'cubic_ou_house' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_house'], 
    'archive_link' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'], $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['link'])['archiveLink'], 
    'link' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'], $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['link'])['imgLink']

  );
  array_push($arr_response['equipment'], $arr ); 
}
print json_encode($arr_response);
?>

