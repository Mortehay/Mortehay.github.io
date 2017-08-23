<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['cable_channel_channel_data_city_eng']) {
  $selectedCity= $_POST['cable_channel_channel_data_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_channel_channel_data_city_eng'];
}  
$promeLink = "/tmp/";
$secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
$fileExtention ="_cable_channels_channels.csv";
$files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
$linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];
$newDBrequest = new dbConnSetClass;
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$fileExtention) {
        $query = "CREATE TEMP TABLE temp(id serial, pit_id_1 integer, pit_id_2 integer, distance varchar(100));select copy_for_testuser('temp( pit_id_1, pit_id_2, distance)', ".$linkStorage.", ';', 'windows-1251');INSERT INTO ".$selectedCity.".".$selectedCity."_cable_channels_channels( pit_id_1, pit_id_2, distance) SELECT pit_id_1, pit_id_2, distance FROM temp t WHERE not exists (SELECT 1 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels c where t.pit_id_1 = c.pit_id_1 and t.pit_id_2 = c.pit_id_2); ";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
      }
    } 
  }
}

$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1 = ".$selectedCity."_cable_channel_pits.pit_number, she_n_1 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_1 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_1_geom = ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE pit_id_1 = ".$selectedCity."_cable_channel_pits.pit_id ; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_2 = ".$selectedCity."_cable_channel_pits.pit_number, she_n_2 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_2 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_2_geom = ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE pit_id_2 = ".$selectedCity."_cable_channel_pits.pit_id; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET channel_geom = ST_MakeLine(pit_1_geom, pit_2_geom), map_distance = st_distance(pit_1_geom, pit_2_geom) WHERE pit_1_geom IS NOT NULL AND pit_2_geom IS NOT NULL;";
$queryArrayKeys = false;
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//add points from line
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1_geom = ST_StartPoint(channel_geom), pit_2_geom = ST_EndPoint(channel_geom) WHERE pit_1_geom IS NULL AND pit_2_geom IS NULL; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1 = ".$selectedCity."_cable_channel_pits.pit_number, pit_id_1 = ".$selectedCity."_cable_channel_pits.pit_id, she_n_1 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_1 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_1_geom = ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ST_Equals(pit_1_geom, ".$selectedCity."_cable_channel_pits.geom)  AND ".$selectedCity."_cable_channels_channels.pit_1_geom IS NOT NULL AND ".$selectedCity."_cable_channel_pits.geom IS NOT NULL AND ".$selectedCity."_cable_channels_channels.pit_id_1 IS NULL; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_2 = ".$selectedCity."_cable_channel_pits.pit_number , pit_id_2 = ".$selectedCity."_cable_channel_pits.pit_id, she_n_2 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_2 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_2_geom = ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ST_Equals(pit_2_geom, ".$selectedCity."_cable_channel_pits.geom) AND ".$selectedCity."_cable_channels_channels.pit_2_geom IS NOT NULL AND ".$selectedCity."_cable_channel_pits.geom IS NOT NULL  AND ".$selectedCity."_cable_channels_channels.pit_id_2 IS NULL;";
$queryArrayKeys = false;
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//this should be at the end

$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET she_1 ='ПГС№'||".$selectedCity."_coverage.coverage_zone FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_1_geom) and ".$selectedCity.".".$selectedCity."_coverage.geom_area is not null; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET she_2 ='ПГС№'||".$selectedCity."_coverage.coverage_zone FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_2_geom) and ".$selectedCity.".".$selectedCity."_coverage.geom_area is not null;";
$queryArrayKeys = false;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//add json data-----------------------

$query = "create temp table t1 as select distinct on(pit_id) geom, pit_id, archive_link from ".$selectedCity.".".$selectedCity."_cable_channel_pits; create temp table t2 as select pit_1_geom, pit_2_geom, channel_geom, pit_id_1, pit_id_2 from ".$selectedCity.".".$selectedCity."_cable_channels_channels; create temp table tmp as (select tj1.pit_id as id, tj1.archive_link, tj1.parents, tj2.children from (select t1.pit_id, t1.archive_link, array_agg(t2.pit_id_2) as parents from t1 right join t2 on t1.pit_id = t2.pit_id_1 where pit_id is not null group by t1.pit_id,t1.archive_link) tj1 join (select t1.pit_id, array_agg(t2.pit_id_1) as children from t1 left join t2 on t1.pit_id = t2.pit_id_2 where pit_id is not null group by t1.pit_id) tj2 on tj1.pit_id = tj2.pit_id) union (select tj1.pit_id as id, tj1.archive_link, tj1.parents , tj2.children from (select t1.pit_id, t1.archive_link, array_agg(t2.pit_id_2) as parents from t1 left join t2 on t1.pit_id = t2.pit_id_1 where pit_id is not null group by t1.pit_id, t1.archive_link) tj1 join (select t1.pit_id, array_agg(t2.pit_id_1) as children from t1 right join t2 on t1.pit_id = t2.pit_id_2 where pit_id is not null group by t1.pit_id) tj2 on tj1.pit_id = tj2.pit_id); create temp table tmp_fixed as (select * from tmp where parents !='{null}') union (select id, archive_link, children as parents, parents as children  from tmp where parents = '{null}'); update ".$selectedCity.".".$selectedCity."_cable_channel_pits set json_data = row_to_json(tmp_fixed) from tmp_fixed where tmp_fixed.id = ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_id;";
$queryArrayKeys = false;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//-------------------------------------
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
echo $query;
?>

