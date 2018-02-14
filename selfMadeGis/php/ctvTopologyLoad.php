<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['ctv_city_topology_load_eng']) {
  $selectedCity= $_POST['ctv_city_topology_load_eng'];
} else {
  $selectedCity = $_REQUEST['ctv_city_topology_load_eng'];
}  
  $promeLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/cubic/";
  $tableType = "_ctv_topology";
  $fileExtention =".csv";
  $files = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['files'];

  $linkStorage = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['linkStorage'];
  $updateState = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['updateState'];
  if ($updateState == 'auto') { $queryModificator = array(
      'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100), SECTOR character varying(100), FLOOR character varying(100), PIPE character varying(100)', 
      'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE, SECTOR, FLOOR, PIPE', 
      'delimiter' =>"','" , 
      'encoding'=>"'utf-8'");
  } else { $queryModificator = array(
    'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100)', 
    'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR, OU_CODE, DATE_REG, COMENT, UNAME, NET_TYPE, HOUSE_ID',
    'delimiter' =>"';'", 
    'encoding'=>"'windows-1251'"
    ); 
  }
  
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$tableType.$fileExtention) {
        $newDBrequest = new dbConnSetClass;
        $query = "CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp(".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; INSERT INTO " . $selectedCity.".".$selectedCity."_ctv_topology(cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name, cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id, cubic_sector, cubic_floor, cubic_pipe) SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID, SECTOR, FLOOR, PIPE FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL);";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
      }
    } 
  }
}
?>

