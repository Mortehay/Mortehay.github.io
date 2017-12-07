<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  

$newDBrequest = new dbConnSetClass;
$query = "SELECT city, links, links_description, region, prime_city, city_eng, map_window from public.links order by city;";
$queryArrayKeys = array('city', 'links', 'links_description', 'region', 'prime_city', 'city_eng', 'map_window');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'city' => $sumObjectsArray[$sumObjectsArrayKey]['city'],
    'links' => $sumObjectsArray[$sumObjectsArrayKey]['links'].$sumObjectsArray[$sumObjectsArrayKey]['links_description'],
    'region' => $sumObjectsArray[$sumObjectsArrayKey]['region'],
    'prime_city' => $sumObjectsArray[$sumObjectsArrayKey]['prime_city'],
    'city_eng' => $sumObjectsArray[$sumObjectsArrayKey]['city_eng'],
    'map_window' => $sumObjectsArray[$sumObjectsArrayKey]['map_window']
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
  //print( $sql);
?>

