<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['city_building_dublicates_finder_eng']) {
  $selectedCity= $_POST['city_building_dublicates_finder_eng'];
} else {
  $selectedCity = $_REQUEST['city_building_dublicates_finder_eng'];
}
$newDBrequest = new dbConnSetClass;
$query = "SELECT openstreet_addr_street, openstreet_addr_housenumber, cubic_street, cubic_house, cubic_cnt, cubic_house_id, cubic_network_type, ST_AsLatLonText(ST_Transform(building_geom_firstpoint, 4326) ) as coords FROM ".$selectedCity.".".$selectedCity."_buildings WHERE cubic_house_id IN (SELECT cubic_house_id FROM  (SELECT openstreet_addr_street, openstreet_addr_housenumber,cubic_house_id, count(*) AS count FROM ".$selectedCity.".".$selectedCity."_buildings  WHERE cubic_house_id IS NOT NULL GROUP BY 1, openstreet_addr_street, openstreet_addr_housenumber,cubic_house_id  ORDER BY count) count WHERE count >1) ORDER BY cubic_house_id, openstreet_addr_street, openstreet_addr_housenumber;";
//echo $query;
$queryArrayKeys = array('openstreet_addr_street', 'openstreet_addr_housenumber', 'cubic_street', 'cubic_house', 'cubic_cnt', 'cubic_house_id', 'cubic_network_type', 'coords');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'openstreet_addr_street' => $sumObjectsArray[$sumObjectsArrayKey]['openstreet_addr_street'],
    'openstreet_addr_housenumber' => $sumObjectsArray[$sumObjectsArrayKey]['openstreet_addr_housenumber'],
    'cubic_street' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_street'],
    'cubic_house' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_house'],
    'cubic_cnt' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_cnt'],
    'cubic_house_id' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_house_id'],
    'cubic_network_type' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_network_type'],
    'building_geom_firstpoint' => $sumObjectsArray[$sumObjectsArrayKey]['coords']
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
?>

