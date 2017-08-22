<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
$server_address = $_SERVER['SERVER_ADDR'];  
if ($_POST['ctv_city_eng']) {$selectedCity= $_POST['ctv_city_eng'];} else {$selectedCity = $_REQUEST['ctv_city_eng'];}
if ($_POST['she']) {$selectedShe= $_POST['she'];} else {$selectedShe = $_REQUEST["she"];} 
if($selectedShe == 'виберіть ПГС') 
  {$deleteNotselectedShe =''; $selectedShe = ''; $selectedSheWhere = ''; $pgsAdr='';} 
else {
  $deleteNotselectedShe= "DELETE FROM temp  WHERE PGS_ADDR <> '".$selectedShe."'; ";
  $selectedSheWhere = " Where cubic_pgs_addr  ='".$selectedShe."'  ";
  $pgsAdr = " AND PGS_ADDR  ='$selectedShe'"; 
  $selectedShe = " AND cubic_pgs_addr  ='".$selectedShe."'  ";
  
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
        $query = "CREATE TEMP TABLE temp( ".$queryModificator['var'].");".$deleteNotselectedShe." select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; CREATE TEMP TABLE alien_cubic_code AS SELECT DISTINCT CODE FROM temp WHERE CODE IS NOT NULL ;DELETE FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code NOT IN(SELECT CODE FROM alien_cubic_code) ;UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = CASE WHEN temp.COMENT LIKE '% № %' THEN replace(temp.COMENT, ' № ', '№') ELSE temp.COMENT END, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_code = temp.CODE and ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = temp.HOUSE_ID and ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = temp.NAME; (SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID, 'missing' as state FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ".$pgsAdr.") UNION ALL (with data(CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID)  as (select CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID  from temp) select d.CITY, d.STREET, d.HOUSE, d.FLAT, d.CODE, d.NAME, d.PGS_ADDR, d.OU_OP_ADDR, d.OU_CODE, d.DATE_REG, d.COMENT, d.UNAME, d.NET_TYPE, d.HOUSE_ID, 'reused code' as rcode from data d where not exists (select 1 from ".$selectedCity.".".$selectedCity."_ctv_topology u where u.cubic_code = d.CODE and u.cubic_house_id = d.HOUSE_ID) and CODE IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ".$pgsAdr."); ";
        $queryArrayKeys = array('CITY','STREET','HOUSE','FLAT','CODE','NAME','PGS_ADDR','OU_OP_ADDR','OU_CODE','DATE_REG','COMENT','UNAME','NET_TYPE','HOUSE_ID', 'state');
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
            'cubic_house_id' => $sumObjectsArray[$sumObjectsArrayKey]['HOUSE_ID'],
            'cubic_equipment_state' => $sumObjectsArray[$sumObjectsArrayKey]['state']
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
$query = "SELECT  cubic_name, cubic_code, json_data  FROM ".$selectedCity.".".$selectedCity."_ctv_topology".$selectedSheWhere.";";
$queryArrayKeys = array('cubic_name', 'cubic_code', 'json_data');
//echo $query;
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$dir_arr_response = array();
$sumObjectsArray = $retuenedArray;
$separator = $file_names_values = '';
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  //print_r($objectArray);
  $description = array(
      'cubic_name' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['label'],
      'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
      'rootDir' => '/var/www/QGIS-Web-Client-master/site/tmp/archive/',
      'subDirType' => '/topology/',
      'json_data' => json_decode($sumObjectsArray[$sumObjectsArrayKey]['json_data'])
    );
  //print_r($description);
  if($description['cubic_name'] !==null){
    array_push($dir_arr_response, $description );
    //print_r($description);
   // echo'<br>';
    topologyDirCreate($description, $selectedCity);
    if($description['json_data'] !== null){
        if(dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/') ){ $file_names = dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/');
        //$description['json_data']['file_names'] = $file_names;
        //echo $description['json_data'];
        //echo '<hr>';print_r($description['json_data']);echo '<hr>';
        //echo '<hr>';print_r($file_names);echo '<hr>';
        $description['json_data']->file_names = $file_names;
        //$description['json_data']['file_names'] = $file_names;
        //echo '<hr>';print_r($description['json_data']);echo '<hr>';
        $file_names_values .= $separator."('".$description['cubic_code']."','".json_encode($description['json_data'] )."')";  
        $separator =",";
        //$query = "CREATE TEMP TABLE tmp(cubic_code varchar(100), json_data text)";
        //$query = "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET json_data = '".json_encode($description['json_data'] )."' WHERE cubic_code = '".$description['cubic_code']."';";
        //echo $query;
        //$newDBrequest -> dbConnect($query, false, true);
      }
    }
    //echo'<hr>';
  }
}
$query = "CREATE TEMP TABLE tmp(cubic_code varchar(100), json_data text); INSERT INTO tmp VALUES ".$file_names_values.";UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET json_data = tmp.json_data FROM tmp WHERE tmp.cubic_code = ".$selectedCity."_ctv_topology.cubic_code ;";
echo $query;
$newDBrequest -> dbConnect($query, false, true);

$link_left_part = /*'"<a href="http://'.$server_address.'/qgis-ck/tmp/archive/'*/'http://'.$server_address.'/qgis-ck/tmp/archive/';
$link_right_part = /*'/" target="_blank">посилання на архів</a>"'*/'/';
$query = "UPDATE $selectedCity"."."."$selectedCity"."_ctv_topology SET archive_link = CASE "." WHEN cubic_name like '%Магистральный распределительный узел%' THEN '$link_left_part"."$selectedCity"."/topology/mdod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптический узел%' THEN '$link_left_part"."$selectedCity"."/topology/nod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптичний приймач%' THEN '$link_left_part"."$selectedCity"."/topology/op/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Передатчик оптический%' THEN '$link_left_part"."$selectedCity"."/topology/ot/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Кросс-муфта%' THEN '$link_left_part"."$selectedCity"."/topology/cc/"."'||cubic_code||'"."$link_right_part' "."END ".$selectedSheWhere.";";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
//-------------------updates such field: she/district/microdistrict
$query = "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET microdistrict =".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity."_ctv_topology.equipment_geom) ".$selectedShe.";".  "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET district =".$selectedCity."_microdistricts.district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity."_ctv_topology.equipment_geom) ".$selectedShe.";" .  "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET she_num =".$selectedCity."_coverage.coverage_zone FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity."_coverage.geom_area, ".$selectedCity."_ctv_topology.equipment_geom) and kiev.kiev_coverage.geom_area is not null ".$selectedShe.";";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
//echo $query;
//json field generation
$query = "CREATE temp table t1 AS select cubic_ou_name, cubic_ou_code, array_agg(cubic_code) AS children from ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_ou_name in ('Кросс-муфта', 'Магистральный распределительный узел', 'Передатчик оптический', 'Оптический узел', 'Оптичний приймач' , 'Оптичний приймач') group by cubic_ou_name, cubic_ou_code; CREATE temp table t2 AS select cubic_code, cubic_ou_code, archive_link ||cubic_code||'_wiring.png' AS archive_link from ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code in (select distinct cubic_ou_code from ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_ou_code is not null);  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET json_data = tmp.json_data from (select t.id AS cubic_code ,row_to_json(t) AS json_data from (select t1.cubic_ou_name AS name, t2.cubic_ou_code AS parents, t1.cubic_ou_code AS id, t1.children,  t2.archive_link from t1 left join t2  on t1.cubic_ou_code = t2.cubic_code) t) tmp where ".$selectedCity."_ctv_topology.cubic_code = tmp.cubic_code AND ".$selectedCity."_ctv_topology.archive_link is not null;";
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);

//echo $query;
?>