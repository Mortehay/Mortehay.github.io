<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['city_roads_OSM_data_eng']) {
  $selectedCity= $_POST['city_roads_OSM_data_eng'];
} else {
  $selectedCity = $_REQUEST['city_roads_OSM_data_eng'];
}  
  $promeLink = "/tmp/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $fileExtention ="_roads_osm.csv";
  $files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
  $linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];
if ($files) {
  if (in_array($selectedCity.$fileExtention, $files)) {

        $newDBrequest = new dbConnSetClass;
        $query = "CREATE temp TABLE tmp (id serial, wkt_geom text, geom geometry, osm_id varchar(100),  name varchar(100), highway varchar(100), maxspeed varchar(100), surface varchar(100), oneway varchar(100), bridge varchar(100), lanes varchar(100)); select copy_for_testuser('tmp (wkt_geom, osm_id,  name, highway, maxspeed, surface, oneway, bridge, lanes)', ".$linkStorage.", ';', 'windows-1251') ; UPDATE tmp SET geom = ST_GeomFromText(wkt_geom, 32636);  UPDATE ".$selectedCity.".".$selectedCity."_roads SET name = tmp.name, highway = tmp.highway, maxspeed = tmp.maxspeed, surface = tmp.surface, oneway = tmp.oneway, bridge = tmp.bridge, lanes = tmp.lanes from tmp where ".$selectedCity."_roads.osm_id = tmp.osm_id and ".$selectedCity."_roads.osm_id is not null;INSERT INTO ".$selectedCity.".".$selectedCity."_roads(geom, osm_id,  name, highway, maxspeed, surface, oneway, bridge, lanes) SELECT geom, osm_id,  name, highway, maxspeed, surface, oneway, bridge, lanes FROM tmp WHERE osm_id NOT IN(SELECT osm_id FROM ".$selectedCity.".".$selectedCity."_roads WHERE osm_id IS NOT NULL); ";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
      }
}  
?>

