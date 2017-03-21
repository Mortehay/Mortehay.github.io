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
        $query = "CREATE TEMP TABLE temp(id serial, pit_1 varchar(100), rm_1 varchar(100), she_1 varchar(100), entry_comment_1 varchar(100), cubic_house_id_1 varchar(100), microdistrict_1 varchar(100),   temp_1 varchar(100), pit_2 varchar(100), rm_2 varchar(100), she_2 varchar(100), entry_comment_2 varchar(100), cubic_house_id_2 varchar(100), microdistrict_2 varchar(100),   temp_2 varchar(100) , distance varchar(100)); select copy_for_testuser('temp( pit_1, rm_1, she_1, entry_comment_1, cubic_house_id_1, microdistrict_1,   temp_1, pit_2, rm_2, she_2, entry_comment_2, cubic_house_id_2, microdistrict_2,   temp_2 , distance)', ".$linkStorage.", ';', 'windows-1251'); INSERT INTO ".$selectedCity.".".$selectedCity."_cable_channels_channels( pit_1, rm_1, she_1, entry_comment_1, cubic_house_id_1, microdistrict_1,   temp_1, pit_2, rm_2, she_2, entry_comment_2, cubic_house_id_2, microdistrict_2,   temp_2 , distance) SELECT pit_1, rm_1, she_1, entry_comment_1, cubic_house_id_1, microdistrict_1,   temp_1, pit_2, rm_2, she_2, entry_comment_2, cubic_house_id_2, microdistrict_2,   temp_2 , distance FROM temp WHERE pit_1 NOT IN(SELECT pit_1 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels) and pit_2 NOT IN(SELECT pit_2 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels) AND she_1 NOT IN(SELECT she_1 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels) AND she_2 NOT IN(SELECT she_2 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels) AND microdistrict_1 NOT IN(SELECT microdistrict_1 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels) AND microdistrict_2 NOT IN(SELECT microdistrict_2 FROM ".$selectedCity.".".$selectedCity."_cable_channels_channels);";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
      }
    } 
  }
}
?>

