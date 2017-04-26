<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['cableChannelChannelDataUpdate.php']) {
  $selectedCity= $_POST['cable_channel_channel_data_city_eng'];
} else {
  $selectedCity = $_REQUEST['cable_channel_channel_data_city_eng'];
}  
$promeLink = "/tmp/";
$secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
$fileExtention ="_cable_channels_channels.csv";
$files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
$linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$fileExtention) {
        $newDBrequest = new dbConnSetClass;
        $query = "CREATE TEMP TABLE temp(id serial, pit_id_1 integer, pit_id_2 integer, distance varchar(100));select copy_for_testuser('temp( pit_id_1, pit_id_2, distance)', ".$linkStorage.", ';', 'windows-1251');INSERT INTO ".$selectedCity.".".$selectedCity."_cable_channels_channels( pit_id_1, pit_id_2, distance) SELECT pit_id_1, pit_id_2, distance FROM temp t WHERE not exists (SELECT 1 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels c where t.pit_id_1 = c.pit_id_1 and t.pit_id_2 = c.pit_id_2); ";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
      }
    } 
  }
}
$newDBrequest = new dbConnSetClass;
$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1 = ".$selectedCity."_cable_channel_pits.pit_number, she_n_1 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_1 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_1_geom = ".$selectedCity."_cable_channel_pits.geom
FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE pit_id_1 = ".$selectedCity."_cable_channel_pits.pit_id ; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_2 = ".$selectedCity."_cable_channel_pits.pit_number, she_n_2 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_2 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_2_geom = ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE pit_id_2 = ".$selectedCity."_cable_channel_pits.pit_id; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET channel_geom = ST_MakeLine(pit_1_geom, pit_2_geom) WHERE pit_1_geom IS NOT NULL AND pit_2_geom IS NOT NULL;";
$queryArrayKeys = false;
echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
?>

