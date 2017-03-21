<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['cityId']) {$selectedCity= $_POST['cityId'];} else {$selectedCity = $_REQUEST['cityId'];}
if ($_POST['tempId']) {$tableId= $_POST['tempId'];} else {$tableId = $_REQUEST['tempId'];}
if ($_POST['cableType']) {$cableType= $_POST['cableType'];} else {$cableType = $_REQUEST['cableType'];}
$geojson = array( 'type' => 'FeatureCollection', 'features' => array());
if ($cableType == 'cc') {
  $newDBrequest = new dbConnSetClass;
  $query = "SELECT summ_tu, summ_contract_sum , summ_sub_contract, summ_acceptance_act, summ_approval_cartogram, summ_route_description, summ_cable_type, summ_archive_link, table_id,  notes2 , rezerve1 , rezerve2 , rezerve3, ST_AsGeoJSON(ST_Transform(geom_cable,3857) ) as cable_geom,  ST_AsGeoJSON(ST_Transform(ST_StartPoint(geom_cable) ,3857) ) as start_point_geom, ST_AsGeoJSON(ST_Transform(ST_EndPoint(geom_cable) ,3857) ) as end_point_geom  FROM ".$selectedCity.".".$selectedCity."_cable_channels WHERE table_id = '$tableId' AND geom_cable IS NOT NULL;";
  $queryArrayKeys = array('summ_tu', 'summ_contract_sum' , 'summ_sub_contract', 'summ_acceptance_act', 'summ_approval_cartogram', 'summ_route_description', 'summ_cable_type', 'summ_archive_link', 'table_id',  'notes2' , 'rezerve1' , 'rezerve2' , 'rezerve3', 'cable_geom', 'start_point_geom', 'end_point_geom');
  $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

   $cable = array(
    'type' => 'Feature',
    'properties' => array(
      'summ_tu' => $retuenedArray[0]['summ_tu'],
      'summ_contract_sum' => $retuenedArray[0]['summ_contract_sum'],
      'summ_sub_contract' => $retuenedArray[0]['summ_sub_contract'],
      'summ_acceptance_act' => $retuenedArray[0]['summ_acceptance_act'],
      'summ_approval_cartogram' => $retuenedArray[0]['summ_approval_cartogram'],
      'summ_route_description' => $retuenedArray[0]['summ_route_description'],
      'summ_cable_type' => $retuenedArray[0]['summ_cable_type'],
      'summ_archive_link' => $retuenedArray[0]['summ_tu'],
      'table_id' => $retuenedArray[0]['table_id'],
      'notes2' => $retuenedArray[0]['notes2'],
      'rezerve1' => $retuenedArray[0]['rezerve1'],
      'rezerve2' => $retuenedArray[0]['rezerve2'],
      'rezerve3' => $retuenedArray[0]['rezerve3']
    ),
    'geometry'=>json_decode($retuenedArray[0]['cable_geom'])
    );
  $startPoint = array(
    'type' =>'Feature',
    'properties' => array(
      'summ_tu' => $retuenedArray[0]['summ_tu'],
      'summ_contract_sum' => $retuenedArray[0]['summ_contract_sum'],
      'summ_sub_contract' => $retuenedArray[0]['summ_sub_contract'],
      'summ_acceptance_act' => $retuenedArray[0]['summ_acceptance_act'],
      'summ_approval_cartogram' => $retuenedArray[0]['summ_approval_cartogram'],
      'summ_route_description' => $retuenedArray[0]['summ_route_description'],
      'summ_cable_type' => $retuenedArray[0]['summ_cable_type'],
      'summ_archive_link' => $retuenedArray[0]['summ_tu'],
      'table_id' => $retuenedArray[0]['table_id'],
      'notes2' => $retuenedArray[0]['notes2'],
      'rezerve1' => $retuenedArray[0]['rezerve1'],
      'rezerve2' => $retuenedArray[0]['rezerve2'],
      'rezerve3' => $retuenedArray[0]['rezerve3']
    ),
    'geometry' =>json_decode($retuenedArray[0]['start_point_geom'])
    );
  $endPoint = array(
    'type' =>'Feature',
    'properties' => array(
      'summ_tu' => $retuenedArray[0]['summ_tu'],
      'summ_contract_sum' => $retuenedArray[0]['summ_contract_sum'],
      'summ_sub_contract' => $retuenedArray[0]['summ_sub_contract'],
      'summ_acceptance_act' => $retuenedArray[0]['summ_acceptance_act'],
      'summ_approval_cartogram' => $retuenedArray[0]['summ_approval_cartogram'],
      'summ_route_description' => $retuenedArray[0]['summ_route_description'],
      'summ_cable_type' => $retuenedArray[0]['summ_cable_type'],
      'summ_archive_link' => $retuenedArray[0]['summ_tu'],
      'table_id' => $retuenedArray[0]['table_id'],
      'notes2' => $retuenedArray[0]['notes2'],
      'rezerve1' => $retuenedArray[0]['rezerve1'],
      'rezerve2' => $retuenedArray[0]['rezerve2'],
      'rezerve3' => $retuenedArray[0]['rezerve3']
    ),
    'geometry' =>json_decode($retuenedArray[0]['end_point_geom'])
    );
}
if ($cableType == 'pkp') {
  $newDBrequest = new dbConnSetClass;
  $query = "SELECT table_id , cable_progect_link , cable_mount_date ,  cable_type , cable_short_type_description , cable_description ,  progect_number ,  cable_purpose , cubic_start_street , cubic_start_house_num,  cubic_start_house_entrance_num ,    cubic_end_street , cubic_end_house_num ,  cubic_end_house_entrance_num , total_cable_length, ST_AsGeoJSON(ST_Transform(geom_cable,3857) ) as cable_geom,  ST_AsGeoJSON(ST_Transform(ST_StartPoint(geom_cable) ,3857) ) as start_point_geom, ST_AsGeoJSON(ST_Transform(ST_EndPoint(geom_cable) ,3857) ) as end_point_geom   FROM ".$selectedCity.".".$selectedCity."_cable_air WHERE table_id = '$tableId' AND geom_cable IS NOT NULL;";
  $queryArrayKeys = array('table_id' , 'cable_progect_link' , 'cable_mount_date' ,  'cable_type' , 'cable_short_type_description' , 'cable_description' ,  'progect_number' ,  'cable_purpose' , 'cubic_start_street' , 'cubic_start_house_num',  'cubic_start_house_entrance_num' ,    'cubic_end_street' , 'cubic_end_house_num' ,  'cubic_end_house_entrance_num' , 'total_cable_length', 'cable_geom', 'start_point_geom' , 'end_point_geom');
  $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
  $cable = array(
    'type' => 'Feature',
    'properties' => array(
      'table_id' => $retuenedArray[0]['table_id'],
      'cable_progect_link' => $retuenedArray[0]['cable_progect_link'],
      'cable_mount_date' => $retuenedArray[0]['cable_mount_date'],
      'cable_type' => $retuenedArray[0]['cable_type'],
      'cable_short_type_description' => $retuenedArray[0]['cable_short_type_description'],
      'cable_description' => $retuenedArray[0]['cable_description'],
      'progect_number' => $retuenedArray[0]['progect_number'],
      'cable_purpose' => $retuenedArray[0]['cable_purpose'],
      'rote_description' => $retuenedArray[0]['cubic_start_street'].",буд.№ ".$retuenedArray[0]['cubic_start_house_num'].",під.№ ".$retuenedArray[0]['cubic_start_house_entrance_num']." - ".$retuenedArray[0]['cubic_end_street'].",буд.№ ".$retuenedArray[0]['cubic_end_house_num'].",під.№ ".$retuenedArray[0]['cubic_end_house_entrance_num'],
      'total_cable_length' => $retuenedArray[0]['total_cable_length']
    ),
    'geometry'=>json_decode($retuenedArray[0]['cable_geom'])
    );
  $startPoint = array(
    'type' => 'Feature',
    'properties' => array(
      'table_id' => $retuenedArray[0]['table_id'],
      'cable_progect_link' => $retuenedArray[0]['cable_progect_link'],
      'cable_mount_date' => $retuenedArray[0]['cable_mount_date'],
      'cable_type' => $retuenedArray[0]['cable_type'],
      'cable_short_type_description' => $retuenedArray[0]['cable_short_type_description'],
      'cable_description' => $retuenedArray[0]['cable_description'],
      'progect_number' => $retuenedArray[0]['progect_number'],
      'cable_purpose' => $retuenedArray[0]['cable_purpose'],
      'rote_description' => $retuenedArray[0]['cubic_start_street'].",буд.№ ".$retuenedArray[0]['cubic_start_house_num'].",під.№ ".$retuenedArray[0]['cubic_start_house_entrance_num']." - ".$retuenedArray[0]['cubic_end_street'].",буд.№ ".$retuenedArray[0]['cubic_end_house_num'].",під.№ ".$retuenedArray[0]['cubic_end_house_entrance_num'],
      'total_cable_length' => $retuenedArray[0]['total_cable_length']
    ),
    'geometry'=>json_decode($retuenedArray[0]['start_point_geom'])
    );
  $endPoint = array(
    'type' => 'Feature',
    'properties' => array(
      'table_id' => $retuenedArray[0]['table_id'],
      'cable_progect_link' => $retuenedArray[0]['cable_progect_link'],
      'cable_mount_date' => $retuenedArray[0]['cable_mount_date'],
      'cable_type' => $retuenedArray[0]['cable_type'],
      'cable_short_type_description' => $retuenedArray[0]['cable_short_type_description'],
      'cable_description' => $retuenedArray[0]['cable_description'],
      'progect_number' => $retuenedArray[0]['progect_number'],
      'cable_purpose' => $retuenedArray[0]['cable_purpose'],
      'rote_description' => $retuenedArray[0]['cubic_start_street'].",буд.№ ".$retuenedArray[0]['cubic_start_house_num'].",під.№ ".$retuenedArray[0]['cubic_start_house_entrance_num']." - ".$retuenedArray[0]['cubic_end_street'].",буд.№ ".$retuenedArray[0]['cubic_end_house_num'].",під.№ ".$retuenedArray[0]['cubic_end_house_entrance_num'],
      'total_cable_length' => $retuenedArray[0]['total_cable_length']
    ),
    'geometry'=>json_decode($retuenedArray[0]['ebd_point_geom'])
    );
}
//echo $query;
//print_r($retuenedArray);
array_push($geojson['features'], $startPoint);
array_push($geojson['features'], $cable);
array_push($geojson['features'], $endPoint);
print json_encode($geojson);
?>

