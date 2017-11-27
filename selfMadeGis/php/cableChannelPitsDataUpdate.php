<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['cable_channel_pits_city_eng']) {
  $selectedCity= $_POST['cable_channel_pits_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_channel_pits_city_eng'];
}
$newDBrequest = new dbConnSetClass;
$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET microdistrict =".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET district =".$selectedCity."_microdistricts.district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET pit_district =".$selectedCity."_coverage.notes FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) and ".$selectedCity.".".$selectedCity."_coverage.geom_area is not null; update ".$selectedCity.".".$selectedCity."_cable_channel_pits set archive_link = 'http://".$newDBrequest->getProp('outerIp')."/qgis-ck/tmp/archive/kiev/topology/pits/'||pit_id||'/';";
$queryArrayKeys = false;
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$query = "create temp table tmp as select pits.pit_id, roads.name from ".$selectedCity.".".$selectedCity."_cable_channel_pits pits right join ".$selectedCity.".".$selectedCity."_roads roads on(ST_DWithin(roads.geom,pits.geom,30))  where pits.street is null; update  ".$selectedCity.".".$selectedCity."_cable_channel_pits set street = tmp.name from tmp where tmp.pit_id = ".$selectedCity."_cable_channel_pits.pit_id; drop table tmp;create temp table tmp as select pits.pit_id, buildings.cubic_street from ".$selectedCity.".".$selectedCity."_cable_channel_pits pits right join ".$selectedCity.".".$selectedCity."_buildings buildings on(ST_DWithin(buildings.building_geom,pits.geom,20))  where pits.street is null; update  ".$selectedCity.".".$selectedCity."_cable_channel_pits set street = tmp.cubic_street from tmp where tmp.pit_id = ".$selectedCity."_cable_channel_pits.pit_id; drop table tmp;create temp table tmp as select pits.pit_id, buildings.cubic_street from ".$selectedCity.".".$selectedCity."_cable_channel_pits pits right join ".$selectedCity.".".$selectedCity."_buildings buildings on(ST_DWithin(buildings.building_geom,pits.geom,40))  where pits.street is null; update  ".$selectedCity.".".$selectedCity."_cable_channel_pits set street = tmp.cubic_street from tmp where tmp.pit_id = ".$selectedCity."_cable_channel_pits.pit_id; drop table tmp; create temp table tmp as select pits.pit_id, buildings.cubic_street from ".$selectedCity.".".$selectedCity."_cable_channel_pits pits right join ".$selectedCity.".".$selectedCity."_buildings buildings on(ST_DWithin(buildings.building_geom,pits.geom,60))  where pits.street is null; update  ".$selectedCity.".".$selectedCity."_cable_channel_pits set street = tmp.cubic_street from tmp where tmp.pit_id = ".$selectedCity."_cable_channel_pits.pit_id; drop table tmp; create temp table tmp as select pits.pit_id, buildings.cubic_street from ".$selectedCity.".".$selectedCity."_cable_channel_pits pits right join ".$selectedCity.".".$selectedCity."_buildings buildings on(ST_DWithin(buildings.building_geom,pits.geom,80))  where pits.street is null; update  ".$selectedCity.".".$selectedCity."_cable_channel_pits set street = tmp.cubic_street from tmp where tmp.pit_id = ".$selectedCity."_cable_channel_pits.pit_id; drop table tmp;create temp table tmp as select pits.pit_id, buildings.cubic_street from ".$selectedCity.".".$selectedCity."_cable_channel_pits pits right join ".$selectedCity.".".$selectedCity."_buildings buildings on(ST_DWithin(buildings.building_geom,pits.geom,100))  where pits.street is null; update  ".$selectedCity.".".$selectedCity."_cable_channel_pits set street = tmp.cubic_street from tmp where tmp.pit_id = ".$selectedCity."_cable_channel_pits.pit_id; drop table tmp; create temp table tmp as select pits.pit_id, roads.name from ".$selectedCity.".".$selectedCity."_cable_channel_pits pits right join ".$selectedCity.".".$selectedCity."_roads roads on(ST_DWithin(roads.geom,pits.geom,80))  where pits.street is null; update  ".$selectedCity.".".$selectedCity."_cable_channel_pits set street = tmp.name from tmp where tmp.pit_id = ".$selectedCity."_cable_channel_pits.pit_id; drop table tmp;";
$queryArrayKeys = false;
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

$query = "SELECT DISTINCT pit_id, json_data FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE pit_id IS NOT NULL;";
$queryArrayKeys = array('pit_id', 'json_data');
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
$separator = $file_names_values = '';
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  //print_r($objectArray);
  $description = array(
      'cubic_name' => 'pits',
      'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['pit_id'],
      'rootDir' => '/var/www/QGIS-Web-Client-master/site/tmp/archive/',
      'subDirType' => '/topology/',
      'json_data' => json_decode($sumObjectsArray[$sumObjectsArrayKey]['json_data'])
    );
  if($description['cubic_name'] !==null){
    topologyDirCreate($description, $selectedCity);
    if($description['json_data'] !== null){
      if(dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/') ){ $file_names = dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/');
          $description['json_data']->file_names = $file_names;
          $file_names_values .= $separator."('".$description['cubic_code']."','".json_encode($description['json_data'] )."')";  
          $separator =","; 
      }
    }
  }
}
//echo '<hr>'.$file_names_values.'<hr>';
if ($file_names_values != ''){
  $query = "CREATE TEMP TABLE tmp(pit_id varchar(100), json_data text); INSERT INTO tmp VALUES ".$file_names_values.";UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET json_data = tmp.json_data FROM tmp WHERE tmp.pit_id::int8 = ".$selectedCity."_cable_channel_pits.pit_id ;";
  echo $query;
  $newDBrequest -> dbConnect($query, false, true);
}
//-------------------------------------
?>