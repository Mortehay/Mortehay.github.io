<?php
include('classFunctionStorage.php');
$query = "SELECT e_mail, restriction FROM public.access;";
//echo $query;
$queryArrayKeys = array('id', 'city', 'links', 'links_description');
$arr_response = array('response' => array());
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'id' => (int)$sumObjectsArray[$sumObjectsArrayKey]['id'],
    'city' => $sumObjectsArray[$sumObjectsArrayKey]['city'],
    'links' => postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['links']),
    'links_description' => postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['links_description'])
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
?>