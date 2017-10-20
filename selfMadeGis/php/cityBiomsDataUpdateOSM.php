<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['city_bioms_OSM_data_eng']) {
  $selectedCity= $_POST['city_bioms_OSM_data_eng'];
} else {
  $selectedCity = $_REQUEST['city_bioms_OSM_data_eng'];
}  
  $promeLink = "/tmp/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $fileType = array('_river_line','_river_poly','_park_poly');
  $fileExtention =".csv";
  foreach($fileType as $type){
    $files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $type.$fileExtention)['files'];
    $linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $type.$fileExtention)['linkStorage'];
    print_r($files).'<hr>';
    echo $linkStorage.'<hr>';
    if ($files) {
      if (in_array($selectedCity.$type.$fileExtention, $files)) {
        $newDBrequest = new dbConnSetClass;
          if(in_array($type, array('_river_line','_river_poly'))){
            $query = "CREATE temp TABLE tmp (id serial, openstreet_id_rel varchar(100),  openstreet_natural varchar(100),  openstreet_type varchar(100), openstreet_water varchar(100), openstreet_name varchar(100), wkt text, geom geometry); select copy_for_testuser('tmp (wkt, openstreet_id_rel,   openstreet_natural,  openstreet_type, openstreet_water, openstreet_name)', ".$linkStorage.", ';', 'windows-1251') ;  UPDATE tmp SET geom = ST_GeomFromText(wkt, 32636); INSERT INTO ".$selectedCity.".".$selectedCity."_river(openstreet_id_rel,   openstreet_natural,  openstreet_type, openstreet_water, openstreet_name, wkt, geom) SELECT openstreet_id_rel,   openstreet_natural,  openstreet_type, openstreet_water, openstreet_name, wkt, geom FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_river WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
          } else if(in_array($type, array('_park_poly'))){
            $query = "CREATE temp TABLE tmp (id serial, openstreet_id_rel varchar(100), openstreet_landuse varchar(100), openstreet_type varchar(100), openstreet_leaf_cycle varchar(100), openstreet_leaf_type varchar(100), openstreet_name varchar(100), openstreet_leisure varchar(100), openstreet_natural varchar(100), wkt text, geom geometry); select copy_for_testuser('tmp (wkt, openstreet_id_rel, openstreet_landuse, openstreet_type, openstreet_leaf_cycle, openstreet_leaf_type, openstreet_name, openstreet_leisure, openstreet_natural)', ".$linkStorage.", ';', 'windows-1251') ;  UPDATE tmp SET geom = ST_GeomFromText(wkt, 32636); INSERT INTO ".$selectedCity.".".$selectedCity."_park(openstreet_id_rel, openstreet_landuse, openstreet_type, openstreet_leaf_cycle, openstreet_leaf_type, openstreet_name, openstreet_leisure, openstreet_natural, wkt, geom) SELECT openstreet_id_rel, openstreet_landuse, openstreet_type, openstreet_leaf_cycle, openstreet_leaf_type, openstreet_name, openstreet_leisure, openstreet_natural, wkt, geom FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_park WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
          }
            
            
            $queryArrayKeys = false;
            echo $query;
            $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        }
    }  
  }
  
?>

