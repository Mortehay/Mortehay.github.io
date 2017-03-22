<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['ctv_city_nod_eng']) {
  $selectedCity= $_POST['ctv_city_nod_eng'];
} else {
  $selectedCity = $_REQUEST['ctv_city_nod_eng'];
}
$newDBrequest = new dbConnSetClass;
$query = "DELETE FROM ".$selectedCity.".".$selectedCity."_nod_coverage;  INSERT INTO ".$selectedCity.".".$selectedCity."_nod_coverage(cubic_lname) SELECT DISTINCT cubic_lname FROM ".$selectedCity.".".$selectedCity."_buildings ; UPDATE ".$selectedCity.".".$selectedCity."_nod_coverage SET coverage_geom = (SELECT ST_MakePolygon(g.geom)  FROM (SELECT ST_AddPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom))), ST_StartPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom)))))  AS geom FROM (SELECT building_geom FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_buildings.cubic_lname = ".$selectedCity.".".$selectedCity."_nod_coverage.cubic_lname ) as geom) g);";
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
$query = "UPDATE ".$selectedCity.".".$selectedCity."_nod_coverage SET beauty_geom = st_buffer(st_buffer(coverage_geom,0.00008),0.00008) WHERE coverage_geom IS NOT NULL AND cubic_lname<>'не опр';";
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, false, true); 	
?>

