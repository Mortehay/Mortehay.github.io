<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['city_supply_uso_eng']) {
  $selectedCity= $_POST['city_supply_uso_eng'];
} else {
  $selectedCity = $_REQUEST['city_supply_uso_eng'];
}
$newDBrequest = new dbConnSetClass;
$query = "DELETE FROM ".$selectedCity.".".$selectedCity."_uso_coverage;  INSERT INTO ".$selectedCity.".".$selectedCity."_uso_coverage(cubic_uso) SELECT DISTINCT cubic_uso FROM ".$selectedCity.".".$selectedCity."_buildings ; UPDATE ".$selectedCity.".".$selectedCity."_uso_coverage SET coverage_geom = (SELECT ST_MakePolygon(g.geom)  FROM (SELECT ST_AddPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom))), ST_StartPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom)))))  AS geom FROM (SELECT building_geom FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_buildings.cubic_uso = ".$selectedCity.".".$selectedCity."_uso_coverage.cubic_uso ) as geom) g);";
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
?>

