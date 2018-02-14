<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');

$newDBrequest = new dbConnSetClass;

if ($_POST['building_entrance_OSM_data_update_city_eng']) {
  $selectedCity= $_POST['building_entrance_OSM_data_update_city_eng'];
} else {
  $selectedCity = $_REQUEST['building_entrance_OSM_data_update_city_eng'];
}  
  $promeLink = "/tmp/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $fileExtention ="_entrances_osm.csv";
  $files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
  $linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];

if ($files) {
  if (in_array($selectedCity.$fileExtention, $files)) {
        
        $query = "CREATE temp TABLE tmp (id serial, openstreet_wkt text,openstreet_id_rel varchar(100),openstreet_entrance varchar(100),openstreet_addr_flats varchar(100),openstreet_entrance_ref varchar(100)); select copy_for_testuser('tmp (openstreet_wkt, openstreet_id_rel, openstreet_entrance, openstreet_addr_flats,openstreet_entrance_ref)', ".$linkStorage.", ';', 'windows-1251') ;  INSERT INTO ".$selectedCity.".".$selectedCity."_entrances(openstreet_wkt, openstreet_id_rel, openstreet_entrance, openstreet_addr_flats,openstreet_entrance_ref) SELECT openstreet_wkt, openstreet_id_rel, openstreet_entrance, openstreet_addr_flats,openstreet_entrance_ref FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_entrances WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
        $queryArrayKeys = false;
        //echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

       
  }
}
$query = "UPDATE ".$selectedCity.".".$selectedCity."_entrances SET geom = ST_GeomFromText(openstreet_wkt, 32636) WHERE geom IS NULL;";
$queryArrayKeys = false;
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

$query = 'UPDATE '.$selectedCity.'.'.$selectedCity.'_entrances SET cubic_house_id = '.$selectedCity.'_buildings.cubic_house_id FROM '.$selectedCity.'.'.$selectedCity.'_buildings WHERE '.$selectedCity.'_entrances.cubic_house_id IS NULL AND ST_Contains(ST_Buffer('.$selectedCity.'_buildings.building_geom,0.5), '.$selectedCity.'_entrances.geom) is true and '.$selectedCity.'_buildings.cubic_house_id IN (SELECT cubic_house_id FROM '.$selectedCity.'.'.$selectedCity.'_buildings WHERE building_geom IS NOT NULL AND cubic_house_id IS NOT NULL);';
$queryArrayKeys = false;
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$query = "UPDATE ".$selectedCity.".".$selectedCity."_entrances SET cubic_entrance_id = cubic_house_id||'p'||openstreet_entrance_ref WHERE cubic_house_id IS NOT NULL  AND  cubic_entrance_id  IS NULL;";
$queryArrayKeys = false;
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
// text bug "staírcase"
//$query = "UPDATE ".$selectedCity.".".$selectedCity."_entrances SET openstreet_entrance = 'staircase' WHERE openstreet_entrance = 'staírcase';";
//$queryArrayKeys = false;
//echo $query;
//$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
?>
