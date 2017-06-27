<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['city_supply_to_eng']) {
  $selectedCity= $_POST['city_supply_to_eng'];
} else {
  $selectedCity = $_REQUEST['city_supply_to_eng'];
}
/*$newDBrequest = new dbConnSetClass;
$query = "DELETE FROM ".$selectedCity.".".$selectedCity."_to_coverage; INSERT INTO ".$selectedCity.".".$selectedCity."_to_coverage(cubic_subdep) SELECT DISTINCT cubic_subdep FROM ".$selectedCity.".".$selectedCity."_buildings ;UPDATE ".$selectedCity.".".$selectedCity."_to_coverage SET coverage_geom = (SELECT ST_MakePolygon(g.geom)  FROM (SELECT ST_AddPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom))), ST_StartPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom)))))  AS geom FROM (SELECT building_geom FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_buildings.cubic_subdep = ".$selectedCity.".".$selectedCity."_to_coverage.cubic_subdep ) as geom) g );";
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);*/

$newDBrequest = new dbConnSetClass;
//--------------------------------------------------------------
$query = "CREATE TEMP TABLE temp AS SELECT cubic_subdep,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_subdep is not null group by cubic_subdep order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_to_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_to_coverage(cubic_subdep, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_subdep, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp;";	
$retuenedArray = $newDBrequest -> dbConnect($query, false, true); 
echo $query;

?>

