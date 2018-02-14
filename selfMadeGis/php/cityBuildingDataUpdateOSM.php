<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['city_building_OSM_data_eng']) {
  $selectedCity= $_POST['city_building_OSM_data_eng'];
} else {
  $selectedCity = $_REQUEST['city_building_OSM_data_eng'];
}  
  $promeLink = "/tmp/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $fileExtention ="_buildings_osm.csv";
  $files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
  $linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];
if ($files) {
  if (in_array($selectedCity.$fileExtention, $files)) {
        $newDBrequest = new dbConnSetClass;
        
        try {
          $query = "CREATE temp TABLE tmp (id serial, temp text,openstreet_id_rel varchar(100),openstreet_doggy_id_rel  varchar(100),openstreet_addr_housenumber varchar(100),openstreet_addr_street varchar(100), openstreet_amenity varchar(100),openstreet_building_type varchar(100), openstreet_building_levels varchar(100)); select copy_for_testuser('tmp (temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels)', ".$linkStorage.", ';', 'windows-1251') ;  INSERT INTO ".$selectedCity.".".$selectedCity."_buildings(temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels) SELECT  temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_buildings WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
          //$queryArrayKeys = false;
          echo $query;
          $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
          $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom = ST_GeomFromText(temp, 32636) WHERE building_geom IS NULL;";
          $queryArrayKeys = false;
          //echo $query;
          $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        }
        catch(Exception $e) {
          echo 'Message: ' .$e->getMessage();
        }
        try {
          $query = "CREATE temp TABLE tmp (id serial, temp text,openstreet_id_rel varchar(100),openstreet_doggy_id_rel  varchar(100),openstreet_addr_housenumber varchar(100),openstreet_addr_street varchar(100), openstreet_amenity varchar(100),openstreet_building_type varchar(100), openstreet_building_levels varchar(100)); select copy_for_testuser('tmp (temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels)', ".$linkStorage.", ',', 'windows-1251') ;  INSERT INTO ".$selectedCity.".".$selectedCity."_buildings(temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels) SELECT  temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_buildings WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
          //$queryArrayKeys = false;
          echo $query;
          $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
          $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom = ST_GeomFromText(temp, 32636) WHERE building_geom IS NULL;";
          $queryArrayKeys = false;
          //echo $query;
          $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        }
        catch(Exception $e) {
          echo 'Message: ' .$e->getMessage();
        }
        try {
          $query = "CREATE temp TABLE tmp (id serial, temp text,openstreet_id_rel varchar(100),openstreet_doggy_id_rel  varchar(100),openstreet_addr_housenumber varchar(100),openstreet_addr_street varchar(100), openstreet_amenity varchar(100),openstreet_building_type varchar(100), openstreet_building_levels varchar(100)); select copy_for_testuser('tmp (temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels)', ".$linkStorage.", ',', 'utf-8') ;  INSERT INTO ".$selectedCity.".".$selectedCity."_buildings(temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels) SELECT  temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_buildings WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
          //$queryArrayKeys = false;
          echo $query;
          $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
          $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom = ST_GeomFromText(temp, 32636) WHERE building_geom IS NULL;";
          $queryArrayKeys = false;
          //echo $query;
          $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        }
        catch(Exception $e) {
          echo 'Message: ' .$e->getMessage();
        }

  }
}
?>

