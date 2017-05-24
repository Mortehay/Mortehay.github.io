<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
$server_address = $_SERVER['SERVER_ADDR'];  
if ($_POST['ctv_city_eng']) {$selectedCity= $_POST['ctv_city_eng'];} else {$selectedCity = $_REQUEST['ctv_city_eng'];}
if ($_POST['she']) {$selectedShe= $_POST['she'];} else {$selectedShe = $_REQUEST["she"];} 
if($selectedShe == 'виберіть ПГС') 
  {$deleteNotselectedShe =''; $selectedShe = ''; $selectedSheWhere = '';} 
else {
  $deleteNotselectedShe= "DELETE FROM tmp  WHERE PGS_ADDR <> '".$selectedShe."'; ";
  $selectedShe = " AND cubic_pgs_addr  ='".$selectedShe."'  ";
  $selectedSheWhere = " Where cubic_pgs_addr  ='".$selectedShe."'  ";
  $PGS_ADDR = " AND PGS_ADDR  ='".$selectedShe."'  "; 
}  
  $promeLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/cubic/";
  $tableType = "_ctv_topology";
  $fileExtention =".csv";
  $files = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['files'];

  $linkStorage = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['linkStorage'];
  $updateState = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['updateState'];
  if ($updateState == 'auto') { $queryModificator = array(
      'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100)', 
      'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE', 
      'delimiter' =>"','" , 
      'encoding'=>"'utf-8'");
  } else { $queryModificator = array(
    'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100)', 
    'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR, OU_CODE, DATE_REG, COMENT, UNAME, NET_TYPE, HOUSE_ID',
    'delimiter' =>"';'", 
    'encoding'=>"'windows-1251'"
    ); 
  }
$newDBrequest = new dbConnSetClass;
$arr_response = array('response' => array());
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$tableType.$fileExtention) {
        $query = "CREATE TEMP TABLE temp( ".$queryModificator['var'].");".$deleteNotselectedShe." select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; DELETE FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code NOT IN(SELECT DISTINCT CODE FROM temp) ;UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = temp.COMENT, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE " . $selectedCity.".".$selectedCity."_ctv_topology.cubic_code = temp.CODE; SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL)".$PGS_ADDR.";";
        $queryArrayKeys = array('CITY','STREET','HOUSE','FLAT','CODE','NAME','PGS_ADDR','OU_OP_ADDR','OU_CODE','DATE_REG','COMENT','UNAME','NET_TYPE','HOUSE_ID');
        //echo $query;
       $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        
        $sumObjectsArray = $retuenedArray;
         foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
          $arr = array(
            'cubic_city' => $sumObjectsArray[$sumObjectsArrayKey]['CITY'],
            'cubic_street' => $sumObjectsArray[$sumObjectsArrayKey]['STREET'],
            'cubic_house' => $sumObjectsArray[$sumObjectsArrayKey]['HOUSE'],
            'cubic_flat' => $sumObjectsArray[$sumObjectsArrayKey]['FLAT'],
            'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['CODE'],
            'cubic_name' => $sumObjectsArray[$sumObjectsArrayKey]['NAME'],
            'cubic_pgs_addr' => $sumObjectsArray[$sumObjectsArrayKey]['PGS_ADDR'],
            'cubic_ou_op_addr' => $sumObjectsArray[$sumObjectsArrayKey]['OU_OP_ADDR'],
            'cubic_ou_code' => $sumObjectsArray[$sumObjectsArrayKey]['OU_CODE'],
            'cubic_date_reg' => $sumObjectsArray[$sumObjectsArrayKey]['DATE_REG'],
            'cubic_coment' => $sumObjectsArray[$sumObjectsArrayKey]['COMENT'],
            'cubic_uname' => $sumObjectsArray[$sumObjectsArrayKey]['UNAME'],
            'cubic_net_type' => $sumObjectsArray[$sumObjectsArrayKey]['NET_TYPE'],
            'cubic_house_id' => $sumObjectsArray[$sumObjectsArrayKey]['HOUSE_ID']
         ); 
          array_push($arr_response['response'], $arr );
          
        }
      }
    } 
  }
}
print json_encode($arr_response);

$query = "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = CASE "." WHEN cubic_name LIKE '%Магистральный распределительный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Магістральний оптичний вузол%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Оптический узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Оптичний приймач%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Передатчик оптический%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Порт ОК%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Домовой узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Ответвитель магистральный%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Распределительный стояк%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Магистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Субмагистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Кросс-муфта%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." END FROM  ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id".$selectedShe.";";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
//echo $query;
$query = "CREATE TEMP TABLE tmp AS SELECT cubic_code, equipment_geom, cubic_name, cubic_street, cubic_house FROM ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET mother_equipment_geom = tmp.equipment_geom,  cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE ".$selectedCity."_ctv_topology.mother_equipment_geom IS NOT null AND ".$selectedCity."_ctv_topology.equipment_geom IS NOT NULL".$selectedShe.";";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
//echo $query;
$query = "CREATE TEMP TABLE tmp AS SELECT cubic_name, cubic_street, cubic_house, cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IN (SELECT DISTINCT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL) ".$selectedShe.";UPDATE  ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_name, cubic_ou_house = tmp.cubic_name FROM tmp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true); 	
//echo $query;
$dir_arr_response = array();
$query = "SELECT  cubic_name, cubic_code  FROM ".$selectedCity.".".$selectedCity."_ctv_topology".$selectedSheWhere.";";
$queryArrayKeys = array('cubic_name', 'cubic_code');
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$dir_arr_response = array();
$sumObjectsArray = $retuenedArray;
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  //print_r($objectArray);
  $description = array(
      'cubic_name' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['label'],
      'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    );
  //print_r($description);
  if($description['cubic_name'] !==null){
    array_push($dir_arr_response, $description );
    //print_r($description);
   // echo'<br>';
    topologyDirCreate($description, $selectedCity);
    //echo'<hr>';
  }
}

$link_left_part = /*'"<a href="http://'.$server_address.'/qgis-ck/tmp/archive/'*/'http://'.$server_address.'/qgis-ck/tmp/archive/';
$link_right_part = /*'/" target="_blank">посилання на архів</a>"'*/'/';
$query = "UPDATE $selectedCity"."."."$selectedCity"."_ctv_topology SET archive_link = CASE "." WHEN cubic_name like '%Магистральный распределительный узел%' THEN '$link_left_part"."$selectedCity"."/topology/mdod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптический узел%' THEN '$link_left_part"."$selectedCity"."/topology/nod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптичний приймач%' THEN '$link_left_part"."$selectedCity"."/topology/op/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Передатчик оптический%' THEN '$link_left_part"."$selectedCity"."/topology/ot/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Кросс-муфта%' THEN '$link_left_part"."$selectedCity"."/topology/cc/"."'||cubic_code||'"."$link_right_part' "."END ".$selectedSheWhere.";";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
//-------------------updates such field: she/district/microdistrict
$query = "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET microdistrict =".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity."_ctv_topology.equipment_geom) ".$selectedShe.";".  "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET district =".$selectedCity."_microdistricts.district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity."_ctv_topology.equipment_geom) ".$selectedShe.";" .  "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET she_num =".$selectedCity."_coverage.coverage_zone FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity."_coverage.geom_area, ".$selectedCity."_ctv_topology.equipment_geom) and kiev.kiev_coverage.geom_area is not null ".$selectedShe.";";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
//echo $query;
?>