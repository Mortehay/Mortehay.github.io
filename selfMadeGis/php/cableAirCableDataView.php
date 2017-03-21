<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['cable_air_cable_dataView_city_eng']) {
  $selectedCity= $_POST['cable_air_cable_dataView_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_air_cable_dataView_city_eng'];
}  
$newDBrequest = new dbConnSetClass;
$query = "SELECT table_id , cable_progect_link , cable_mount_date ,  cable_type , cable_short_type_description , cable_description ,  progect_number ,  cable_purpose , cubic_start_street , cubic_start_house_num,  cubic_start_house_entrance_num ,    cubic_end_street , cubic_end_house_num ,  cubic_end_house_entrance_num , total_cable_length   FROM ".$selectedCity.".".$selectedCity."_cable_air WHERE cable_type IS NOT NULL AND cable_short_type_description IS NOT NULL AND geom_cable IS NOT NULL AND  progect_number  IS NOT NULL ORDER BY trim(leading 't_' from table_id)::int;";
$queryArrayKeys = array('table_id' , 'cable_progect_link' , 'cable_mount_date' ,  'cable_type' , 'cable_short_type_description' , 'cable_description' ,  'progect_number' ,  'cable_purpose' , 'cubic_start_street' , 'cubic_start_house_num',  'cubic_start_house_entrance_num' ,    'cubic_end_street' , 'cubic_end_house_num' ,  'cubic_end_house_entrance_num', 'total_cable_length');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//print_r($retuenedArray);
$sumObjectsArray = $retuenedArray;
foreach ($sumObjectsArray as $sumObjectsArrayKey => $$objectArray) {
   $sumObjectsArray[$sumObjectsArrayKey]['rote_description'] = $sumObjectsArray[$sumObjectsArrayKey]['cubic_start_street'] .",буд.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_start_house_num'].",під.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_start_house_entrance_num']." - ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_street'].",буд.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_house_num'].",під.№ ".$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_house_entrance_num'];
   unset($sumObjectsArray[$sumObjectsArrayKey]['cubic_start_street'], $sumObjectsArray[$sumObjectsArrayKey]['cubic_start_house_num'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_start_house_entrance_num'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_street'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_house_num'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_end_house_entrance_num']);
}
//print_r($sumObjectsArray);
$arr_response['response'] =  $sumObjectsArray;
print json_encode($arr_response);
	//print( $sql);
?>

