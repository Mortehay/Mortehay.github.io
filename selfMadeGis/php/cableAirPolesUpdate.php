<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['cable_air_poles_data_city_eng']) {
  $selectedCity= $_POST['cable_air_poles_data_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_air_poles_data_city_eng'];
}  

$newDBrequest = new dbConnSetClass;


$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_air_poles SET pole_street = ".$selectedCity."_roads.name FROM ".$selectedCity.".".$selectedCity."_roads WHERE ST_Intersects(".$selectedCity.".".$selectedCity."_roads.geom, ST_Buffer(".$selectedCity.".".$selectedCity."_cable_air_poles.geom,20)) and ".$selectedCity.".".$selectedCity."_roads.geom is not null;UPDATE ".$selectedCity.".".$selectedCity."_cable_air_poles SET pole_micro_district = ".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity."_cable_air_poles.geom) and ".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom is not null;";
$queryArrayKeys = false;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

echo $query;
?>

