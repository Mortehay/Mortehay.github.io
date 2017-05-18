<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['ethernet_topology_dataView_city_eng']) {$selectedCity= $_POST['ethernet_topology_dataView_city_eng'];} else {$selectedCity = $_REQUEST["ethernet_topology_dataView_city_eng"];}
$arr_response = array('nodes' => array(),'links' => array(),'mdods' => array()); 
$newDBrequest = new dbConnSetClass;
$query = "SELECT DISTINCT ON (cubic_switch_id) cubic_city, cubic_street, cubic_house_num, cubic_switch_role, cubic_switch_id, cubic_parent_switch_id, cubic_switch_role, cubic_inventary_state, cubic_switch_agr_id   FROM ".$selectedCity.".".$selectedCity."_switches;";
//echo $query;
$queryArrayKeys = array('cubic_city', 'cubic_street', 'cubic_house_num', 'cubic_switch_role', 'cubic_switch_id', 'cubic_parent_switch_id', 'cubic_switch_role', 'cubic_inventary_state','cubic_switch_agr_id');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'id' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_id'],
    'title' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_role'].' - '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_street'].' , № '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_house_num'].'  '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_inventary_state'],
    'group' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_role'])['group'],
    'color' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_role'])['color'],
    'label' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_role'])['label'].' - '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_street'].' , № '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_house_num'],
    'comment' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_inventary_state'],
    'agr' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_agr_id'],
  );
  array_push($arr_response['nodes'], $arr ); 
}

$query = "SELECT cubic_switch_id, cubic_parent_switch_id, cubic_switch_role, cubic_switch_agr_id  FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_parent_switch_id IN(SELECT  distinct cubic_switch_id FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IS NOT NULL);";
//echo $query;
$queryArrayKeys = array('cubic_switch_id', 'cubic_parent_switch_id', 'cubic_switch_role', 'cubic_switch_agr_id');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'from' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_id'],
    'to' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_parent_switch_id'],
    'value' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_role'])['value'],
    'color' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_role'])['color'],
    'agr' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_agr_id'],
  );
  array_push($arr_response['links'], $arr ); 
}
$query = "SELECT DISTINCT cubic_city, cubic_street, cubic_house_num, cubic_switch_role, cubic_switch_id, cubic_parent_switch_id, cubic_inventary_state, cubic_switch_id FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_role IN('agr');";
//echo $query;
$queryArrayKeys = array('cubic_city', 'cubic_street', 'cubic_house_num', 'cubic_switch_role', 'cubic_switch_id', 'cubic_parent_switch_id', 'cubic_inventary_state', 'cubic_switch_id');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'id' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_id'],
    'title' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_role'].' - '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_street'].' , № '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_house_num'].'  '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_inventary_state'],
    'comment' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_inventary_state'],
    'agr' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_switch_id'],
  );
  array_push($arr_response['mdods'], $arr ); 
}
print json_encode($arr_response);
?>

