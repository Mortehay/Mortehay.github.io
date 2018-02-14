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
$query = 'select row_number() over (order by equipment_state) as "group",equipment_name as group_name, equipment_state as group_tech from '.$selectedCity.'.'.$selectedCity.'_equipment_statistics group by equipment_name, equipment_state order by equipment_state, equipment_name';
//echo $query;
$queryArrayKeys = array('group','group_name','group_tech');
$cityEquipmentNames = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$arr_response['response']['names'] = $cityEquipmentNames; 
$query = 'select p_t.group, p_t.group_name, p_t.group_tech, a_l.equipment_count as y, a_l.update_time::date as x from '.$selectedCity.'.'.$selectedCity.'_equipment_statistics a_l left join (select row_number() over (order by equipment_state) as "group",equipment_name as group_name, equipment_state as group_tech from '.$selectedCity.'.'.$selectedCity.'_equipment_statistics group by equipment_name, equipment_state order by equipment_state, equipment_name) p_t on(p_t.group_name = a_l.equipment_name );';
//echo $query;
$queryArrayKeys = array('group', 'group_name', 'group_tech', 'y','x');
$cityEquipmentValues = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$arr_response['response']['values'] = $cityEquipmentValues; 

print json_encode($arr_response);
?>

