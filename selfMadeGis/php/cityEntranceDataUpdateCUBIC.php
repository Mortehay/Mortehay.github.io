<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['building_entrance_CUBIC_data_update_city_eng']) {
  $selectedCity= $_POST['building_entrance_CUBIC_data_update_city_eng'];
} else {
  $selectedCity = $_REQUEST['building_entrance_CUBIC_data_update_city_eng'];
}  
  $promeLink = "/tmp/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $fileExtention ="_entrances_cubic.csv";
  $files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
  $linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$fileExtention) {
        $newDBrequest = new dbConnSetClass;
        $query = "CREATE temp TABLE tmp (id serial, cubic_entrance_id varchar(100), cubic_entrance_number varchar(100),cubic_entrance_floor_num varchar(100),cubic_entrance_flat_num varchar(100),cubic_house_id varchar(100)); select copy_for_testuser('tmp ( cubic_entrance_number, cubic_entrance_floor_num, cubic_entrance_flat_num,cubic_house_id)', ".$linkStorage.", ';', 'windows-1251') ;UPDATE tmp SET cubic_entrance_id = cubic_house_id||'p'||cubic_entrance_number;  INSERT INTO ".$selectedCity.".".$selectedCity."_entrances(cubic_entrance_id, cubic_entrance_number, cubic_entrance_floor_num, cubic_entrance_flat_num,cubic_house_id) SELECT cubic_entrance_id, cubic_entrance_number, cubic_entrance_floor_num, cubic_entrance_flat_num,cubic_house_id FROM tmp WHERE cubic_entrance_id NOT IN(SELECT cubic_entrance_id FROM ".$selectedCity.".".$selectedCity."_entrances WHERE cubic_entrance_id IS NOT NULL);DROP TABLE tmp;";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "UPDATE ".$selectedCity.".".$selectedCity."_entrances SET geom = building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_entrances.geom IS NULL AND ".$selectedCity.".".$selectedCity."_entrances.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
      }
    } 
  }
}
?>

