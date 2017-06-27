<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['ctv_city_nod_eng']) {
  $selectedCity= $_POST['ctv_city_nod_eng'];
} else {
  $selectedCity = $_REQUEST['ctv_city_nod_eng'];
}
$newDBrequest = new dbConnSetClass;
//--------------------------------------------------------------
$query = "CREATE TEMP TABLE temp AS SELECT cubic_lname, cubic_ladress,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_lname not in('не опр') group by cubic_lname, cubic_ladress order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_nod_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_nod_coverage(cubic_lname, cubic_ladress, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_lname, cubic_ladress, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp; ";	
$retuenedArray = $newDBrequest -> dbConnect($query, false, true); 
echo $query;
?>

