<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['cable_channel_cable_dataView_city_eng']) {$selectedCity= $_POST['cable_channel_cable_dataView_city_eng'];} else {$selectedCity = $_REQUEST["cable_channel_cable_dataView_city_eng"];} 
$newDBrequest = new dbConnSetClass;
$query = "SELECT summ_tu, summ_contract_sum , summ_sub_contract, summ_acceptance_act, summ_approval_cartogram, summ_route_description, summ_cable_type, summ_archive_link, table_id,  notes2 , rezerve1 , rezerve2 , rezerve3, CASE WHEN geom_cable IS NULL THEN '-' ELSE '+' END as geom_state  FROM ".$selectedCity.".".$selectedCity."_cable_channels WHERE summ_tu IS NOT NULL AND summ_cable_type IS NOT NULL ORDER BY trim(leading 'ПГС-' from rezerve3)::int, trim(leading 't_' from table_id)::int ;";
//echo $query;
$queryArrayKeys = array('summ_tu', 'summ_contract_sum' , 'summ_sub_contract', 'summ_acceptance_act', 'summ_approval_cartogram', 'summ_route_description', 'summ_cable_type', 'summ_archive_link', 'table_id',  'notes2' , 'rezerve1' , 'rezerve2' , 'rezerve3', 'geom_state');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());

$checkList = array('check' => array ('акт','дог','погодж','ту','ду','договір'), 'response' => array ('summ_acceptance_act', 'summ_contract_sum','summ_approval_cartogram', 'summ_tu','summ_sub_contract', 'summ_contract_sum'));

foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $folderLink = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/cc/'.$sumObjectsArray[$sumObjectsArrayKey]['table_id'].'/';
  $arr = array(
    'summ_tu' => $sumObjectsArray[$sumObjectsArrayKey]['summ_tu'],
    'summ_tu_date' =>  fileDate($folderLink, $checkList, 'summ_tu'),
    'summ_contract_sum' => $sumObjectsArray[$sumObjectsArrayKey]['summ_contract_sum'], 
    'summ_contract_sum_date' => fileDate($folderLink, $checkList, 'summ_contract_sum'),
    'summ_sub_contract' => $sumObjectsArray[$sumObjectsArrayKey]['summ_sub_contract'], 
    'summ_sub_contract_date' => fileDate($folderLink, $checkList, 'summ_sub_contract'),
    'summ_acceptance_act' => $sumObjectsArray[$sumObjectsArrayKey]['summ_acceptance_act'],
    'summ_acceptance_act_date' => fileDate($folderLink, $checkList, 'summ_acceptance_act'),
    'summ_approval_cartogram' => $sumObjectsArray[$sumObjectsArrayKey]['summ_approval_cartogram'], 
    'summ_approval_cartogram_date' => fileDate($folderLink, $checkList, 'summ_approval_cartogram'),
    'summ_route_description' => $sumObjectsArray[$sumObjectsArrayKey]['summ_route_description'],
    'summ_cable_type' => $sumObjectsArray[$sumObjectsArrayKey]['summ_cable_type'],
    'summ_archive_link' => $sumObjectsArray[$sumObjectsArrayKey]['summ_archive_link'],
    'table_id' => $sumObjectsArray[$sumObjectsArrayKey]['table_id'],
    'notes2' => $sumObjectsArray[$sumObjectsArrayKey]['notes2'],
    'rezerve1' => $sumObjectsArray[$sumObjectsArrayKey]['rezerve1'],
    'rezerve2' => $sumObjectsArray[$sumObjectsArrayKey]['rezerve2'],
    'rezerve3' => $sumObjectsArray[$sumObjectsArrayKey]['rezerve3'],
    'geom_state' => $sumObjectsArray[$sumObjectsArrayKey]['geom_state']
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
?>

