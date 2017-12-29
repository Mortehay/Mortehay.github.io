<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['cable_air_cable_dataView_city_eng']) {
  $selectedCity= $_POST['cable_air_cable_dataView_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_air_cable_dataView_city_eng'];
}  
$newDBrequest = new dbConnSetClass;
$query = "SELECT table_id , cable_progect_link , cable_mount_date ,  cable_type , cable_short_type_description , cable_description ,  progect_number ,  cable_purpose , cubic_start_street , cubic_start_house_num,  cubic_start_house_entrance_num ,    cubic_end_street , cubic_end_house_num ,  cubic_end_house_entrance_num , total_cable_length, CASE WHEN geom_cable IS NULL THEN '-' ELSE '+' END as geom_state   FROM ".$selectedCity.".".$selectedCity."_cable_air WHERE cable_type IS NOT NULL AND cable_short_type_description IS NOT NULL AND  progect_number  IS NOT NULL ORDER BY trim(leading 't_' from table_id)::int;";
$queryArrayKeys = array('table_id' , 'cable_progect_link' , 'cable_mount_date' ,  'cable_type' , 'cable_short_type_description' , 'cable_description' ,  'progect_number' ,  'cable_purpose' , 'cubic_start_street' , 'cubic_start_house_num',  'cubic_start_house_entrance_num' ,    'cubic_end_street' , 'cubic_end_house_num' ,  'cubic_end_house_entrance_num', 'total_cable_length','geom_state');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//echo $query;
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'table_id' => $sumObjectsArray[$sumObjectsArrayKey]['table_id'],
    'cable_progect_link' => $sumObjectsArray[$sumObjectsArrayKey]['cable_progect_link'],
    'cable_mount_date' => $sumObjectsArray[$sumObjectsArrayKey]['cable_mount_date'],
    'cable_type' => $sumObjectsArray[$sumObjectsArrayKey]['cable_type'],
    'cable_short_type_description' => $sumObjectsArray[$sumObjectsArrayKey]['cable_short_type_description'],
    'cable_description' => $sumObjectsArray[$sumObjectsArrayKey]['cable_description'],
    'progect_number' => $sumObjectsArray[$sumObjectsArrayKey]['progect_number'],
    'cable_purpose' => $sumObjectsArray[$sumObjectsArrayKey]['cable_purpose'],
    'total_cable_length' => $sumObjectsArray[$sumObjectsArrayKey]['total_cable_length'],
    'rote_description' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_start_street'] .",буд.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_start_house_num'].",під.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_start_house_entrance_num']." - ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_street'].",буд.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_house_num'].",під.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_house_entrance_num'],
    'geom_state' => $sumObjectsArray[$sumObjectsArrayKey]['geom_state']
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
	//print( $sql);
?>

