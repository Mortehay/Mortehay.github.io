<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['cable_channel_city_eng']) {
  $selectedCity= $_POST['cable_channel_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_channel_city_eng'];
}
$newDBrequest = new dbConnSetClass;
$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1_geom =  ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_number = ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_1 AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_district  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.she_1  AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.microdistrict  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.microdistrict_1; "."UPDATE " .$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_2_geom =  ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_number = ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_2 AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_district  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.she_2  AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.microdistrict  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.microdistrict_2; "."UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET channel_geom = ST_MakeLine(pit_1_geom, pit_2_geom) WHERE pit_1_geom IS NOT NULL AND pit_2_geom IS NOT NULL;";
$queryArrayKeys = false;
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
?>

