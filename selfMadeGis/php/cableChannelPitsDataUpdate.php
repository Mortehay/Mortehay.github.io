<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['cable_channel_pits_city_eng']) {
  $selectedCity= $_POST['cable_channel_pits_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_channel_pits_city_eng'];
}
$newDBrequest = new dbConnSetClass;
$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET microdistrict =".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET district =".$selectedCity."_microdistricts.district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET pit_district =".$selectedCity."_coverage.notes FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) and ".$selectedCity.".".$selectedCity."_coverage.geom_area is not null; update ".$selectedCity.".".$selectedCity."_cable_channel_pits set archive_link = 'http://10.112.129.170/qgis-ck/tmp/archive/kiev/topology/pits/'||pit_id||'/';";
$queryArrayKeys = false;
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

//-------------------------------------
?>