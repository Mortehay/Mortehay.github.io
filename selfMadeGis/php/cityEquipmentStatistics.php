<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['equipment_statistics_city_eng']) {$selectedCity= $_POST['equipment_statistics_city_eng'];} else {$selectedCity = $_REQUEST["equipment_statistics_city_eng"];}

$newDBrequest = new dbConnSetClass;
$arr_response = array('response' => array());
$query = "SELECT distinct equipment_state FROM ".$selectedCity.".".$selectedCity."_equipment_statistics WHERE equipment_name is not null ;";
//echo $query;
$queryArrayKeys = array('equipment_state');
$cityEquipmentStates = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$arr_response['response']['states'] = $cityEquipmentStates; 
$query = "SELECT distinct equipment_name,equipment_state FROM ".$selectedCity.".".$selectedCity."_equipment_statistics WHERE equipment_name is not null ;";
//echo $query;
$queryArrayKeys = array('equipment_name','equipment_state');
$cityEquipmentNames = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$arr_response['response']['names'] = $cityEquipmentNames; 
$query = "SELECT equipment_name, equipment_state, equipment_count, update_time::date FROM ".$selectedCity.".".$selectedCity."_equipment_statistics WHERE equipment_name is not null ;";
//echo $query;
$queryArrayKeys = array('equipment_name', 'equipment_state', 'equipment_count', 'update_time');
$cityEquipmentValues = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$arr_response['response']['values'] = $cityEquipmentValues; 

print json_encode($arr_response);
?>

