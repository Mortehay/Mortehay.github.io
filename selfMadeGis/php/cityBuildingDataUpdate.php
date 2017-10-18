<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['city_building_data_eng']) {
  $selectedCity= $_POST['city_building_data_eng'];
} else {
  $selectedCity = $_REQUEST['city_building_data_eng'];
}  
  //$promeLink = "/tmp/cubic/_buildings/";
  //$secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
 // $fileExtention ="_buildings.csv";
  //$files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
  //print_r($files);
  //$linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];
  //print_r($linkStorage);
///////////////
$promeLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
$secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/cubic/";
$tableType = "_buildings";
$fileExtention =".csv";
$files = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['files'];

$linkStorage = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['linkStorage'];
$updateState = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['updateState'];
//echo $files.'<hr>';
//echo $updateState.'<hr>';
//echo $linkStorage.'<hr>';
if ($updateState == 'auto') { $queryModificator = array(
    'var' => 'id serial, CITY character varying(100),REGION character varying(100), DISTR_NEW character varying(100), STREET character varying(100),HOUSE character varying(100), COMM text,CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100), LNAME character varying(100),LADRESS character varying(100), HPNAME character varying(100),HPADRESS character varying(100), HPCODE character varying(100), FREQ character varying(100),DATE_BUILDING character varying(100), DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100), DIGITAL_SEGMENT character varying(100), DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100), BOX_TYPE character varying(100),HOUSE_ID character varying(100), SECTOR_CNT character varying(100),CNT character varying(100), PARNET text, SERV_PARNET text, NETTYPE character varying(100), CNT_ATV character varying(100),CNT_VBB character varying(100), CNT_ETH character varying(100),CNT_DOCSIS character varying(100), CNT_KTV character varying(100), CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100), MAX_SPEED_DOCSIS character varying(100), REPORT_DATE character varying(100)', 
    'val'=> 'CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE, CNT_ATV, CNT_VBB, CNT_ETH, CNT_DOCSIS, CNT_KTV,CNT_ACTIVE_CONTR, MAX_SPEED_ETHERNET, MAX_SPEED_DOCSIS, REPORT_DATE', 
    'delimiter' =>"','" , 
    'encoding'=>"'utf-8'");
} elseif ($updateState == 'manual') { $queryModificator = array(
  'var' => 'id serial, CITY character varying(100), REGION character varying(100), DISTR_NEW character varying(100), STREET character varying(100), HOUSE character varying(100), SUBDEP character varying(100), USO character varying(100), LNAME character varying(100), LADRESS character varying(100), HPNAME character varying(100), HPADRESS character varying(100), NETTYPE character varying(100), FREQ character varying(100), HOUSE_TYPE character varying(100), CSD character varying(100), CNT character varying(100), COMM text, CNT_VBB character varying(100), CNT_ETH character varying(100), CNT_DOCSIS character varying(100), CNT_KTV character varying(100), CNT_ATV character varying(100), CNT_ACTIVE_CONTR character varying(100), DATE_BUILDING character varying(100), DATE_BUILDING_ETH character varying(100), DATE_CT character varying(100), SEGMENT character varying(100), DIGITAL_SEGMENT character varying(100), DIGITAL_STAGE character varying(100), DIGITAL_DATE character varying(100), BOX_TYPE character varying(100), HOUSE_ID character varying(100), PARNET text, SERV_PARNET text, SECTOR_CNT character varying(100), HPCODE character varying(100), MAX_SPEED_ETHERNET character varying(100), MAX_SPEED_DOCSIS character varying(100)', 
  'val'=> 'CITY, REGION, DISTR_NEW, STREET, HOUSE,  SUBDEP, USO, LNAME, LADRESS, HPNAME, HPADRESS, NETTYPE, FREQ, HOUSE_TYPE, CSD, CNT, COMM, CNT_VBB, CNT_ETH, CNT_DOCSIS, CNT_KTV, CNT_ATV, CNT_ACTIVE_CONTR, DATE_BUILDING, DATE_BUILDING_ETH, DATE_CT, SEGMENT, DIGITAL_SEGMENT, DIGITAL_STAGE, DIGITAL_DATE, BOX_TYPE, HOUSE_ID, PARNET, SERV_PARNET, SECTOR_CNT, HPCODE, MAX_SPEED_ETHERNET, MAX_SPEED_DOCSIS',
  'delimiter' =>"';'", 
  'encoding'=>"'windows-1251'"
  ); 
}
  /////////////////
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$tableType.$fileExtention) {
        $newDBrequest = new dbConnSetClass;
        $query = "CREATE TEMP TABLE temp(".$queryModificator['var']."); select copy_for_testuser('temp(".$queryModificator['val'].")', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = temp.CITY, cubic_region = temp.REGION, cubic_distr_new = temp.DISTR_NEW, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_subdep = temp.SUBDEP, cubic_uso = temp.USO, cubic_lname = temp.LNAME, cubic_ladress = temp.LADRESS, cubic_hpname = temp.HPNAME, cubic_hpadress = temp.HPADRESS, cubic_network_type = temp.NETTYPE, cubic_freq = temp.FREQ, cubic_house_type = temp.HOUSE_TYPE, cubic_csd = temp.CSD, cubic_cnt = temp.CNT, cubic_comm = temp.COMM, cubic_cnt_vbb = temp.CNT_VBB, cubic_cnt_eth = temp.CNT_ETH, cubic_cnt_docsis = temp.CNT_DOCSIS, cubic_cnt_ktv = temp.CNT_KTV, cubic_cnt_atv = temp.CNT_ATV, cubic_cnt_active_contr = temp.CNT_ACTIVE_CONTR, cubic_date_building = temp.DATE_BUILDING, cubic_date_building_eth = temp.DATE_BUILDING_ETH, cubic_date_ct = temp.DATE_CT, cubic_segment = temp.SEGMENT, cubic_digital_segment = temp.DIGITAL_SEGMENT, cubic_digital_stage = temp.DIGITAL_STAGE, cubic_digital_date = temp.DIGITAL_DATE, cubic_box_type = temp.BOX_TYPE, cubic_house_id = temp.HOUSE_ID, cubic_parnet = temp.PARNET, cubic_serv_parnet = temp.SERV_PARNET, cubic_sector_cnt = temp.SECTOR_CNT, cubic_hpcode = temp.HPCODE, cubic_max_speed_ethernet = temp.MAX_SPEED_ETHERNET, cubic_max_speed_docsis = temp.MAX_SPEED_DOCSIS FROM  temp WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id = temp.HOUSE_ID; DROP TABLE temp;";
        $queryArrayKeys = false;
        //echo $query.'<hr>';
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = NULL, cubic_region = NULL, cubic_distr_new = NULL, cubic_street = NULL, cubic_house = NULL, cubic_subdep = NULL, cubic_uso = NULL, cubic_lname = NULL, cubic_ladress = NULL, cubic_hpname = NULL, cubic_hpadress = NULL, cubic_network_type =NULL, cubic_freq = NULL, cubic_house_type = NULL, cubic_csd = NULL, cubic_cnt = NULL, cubic_comm = NULL, cubic_cnt_vbb = NULL, cubic_cnt_eth = NULL, cubic_cnt_docsis = NULL, cubic_cnt_ktv = NULL, cubic_cnt_atv = NULL, cubic_cnt_active_contr = NULL, cubic_date_building = NULL, cubic_date_building_eth = NULL, cubic_date_ct = NULL, cubic_segment = NULL, cubic_digital_segment = NULL, cubic_digital_stage = NULL, cubic_digital_date = NULL, cubic_box_type = NULL, cubic_parnet = NULL, cubic_serv_parnet = NULL, cubic_sector_cnt = NULL, cubic_hpcode = NULL, cubic_max_speed_ethernet = NULL, cubic_max_speed_docsis = NULL  WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id IS NULL;";
        $queryArrayKeys = false;
        //echo $query.'<hr>';
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_firstpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_firstpoint IS NULL ;  UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_secondpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),2), 32636) WHERE building_geom_secondpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_thirdpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),3), 32636) WHERE building_geom_thirdpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_fourthpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),4), 32636) WHERE building_geom_fourthpoint IS NULL ;";
        $queryArrayKeys = false;
        //echo $query.'<hr>';
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        //coverage update////
        $query = "CREATE TEMP TABLE temp AS SELECT cubic_lname, cubic_ladress,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_lname not in('не опр') group by cubic_lname, cubic_ladress order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_nod_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_nod_coverage(cubic_lname, cubic_ladress, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_lname, cubic_ladress, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp; ";
        $queryArrayKeys = false;
        //echo $query.'<hr>';
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

        $query = "CREATE TEMP TABLE temp AS SELECT cubic_subdep,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(case when cubic_cnt ~ '^[0-9\.]+$' then cubic_cnt::integer else 0 end) as cubic_cnt, sum(case when cubic_cnt_docsis ~ '^[0-9\.]+$' then cubic_cnt_docsis::integer else 0 end) as cubic_cnt_docsis, sum(case when cubic_cnt_ktv ~ '^[0-9\.]+$' then cubic_cnt_ktv::integer else 0 end) as cubic_cnt_ktv, sum(case when cubic_cnt_atv ~ '^[0-9\.]+$' then cubic_cnt_atv::integer else 0 end) as cubic_cnt_atv, sum(case when cubic_cnt_vbb ~ '^[0-9\.]+$' then cubic_cnt_vbb::integer else 0 end) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(case when cubic_cnt_active_contr ~ '^[0-9\.]+$' then cubic_cnt_active_contr::integer else 0 end) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_subdep is not null group by cubic_subdep order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_to_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_to_coverage(cubic_subdep, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_subdep, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp;";

        $queryArrayKeys = false;
        //echo $query.'<hr>';
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "CREATE TEMP TABLE temp AS SELECT cubic_uso,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(case when cubic_cnt ~ '^[0-9\.]+$' then cubic_cnt::integer else 0 end) as cubic_cnt, sum(case when cubic_cnt_docsis ~ '^[0-9\.]+$' then cubic_cnt_docsis::integer else 0 end) as cubic_cnt_docsis, sum(case when cubic_cnt_ktv ~ '^[0-9\.]+$' then cubic_cnt_ktv::integer else 0 end) as cubic_cnt_ktv, sum(case when cubic_cnt_atv ~ '^[0-9\.]+$' then cubic_cnt_atv::integer else 0 end) as cubic_cnt_atv, sum(case when cubic_cnt_vbb ~ '^[0-9\.]+$' then cubic_cnt_vbb::integer else 0 end) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(case when cubic_cnt_active_contr ~ '^[0-9\.]+$' then cubic_cnt_active_contr::integer else 0 end) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_uso is not null group by cubic_uso order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_uso_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_uso_coverage(cubic_uso, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_uso, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp;";
        $queryArrayKeys = false;
        //echo $query.'<hr>';
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        /////////////////////
        $query = "CREATE TEMP TABLE temp(".$queryModificator['var']."); select copy_for_testuser('temp(".$queryModificator['val'].")', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; SELECT CITY, STREET, HOUSE, HOUSE_ID, CNT FROM temp WHERE HOUSE_ID  NOT IN(SELECT cubic_house_id FROM ".$selectedCity.".".$selectedCity."_buildings WHERE cubic_house_id IS NOT NULL) IS TRUE AND NETTYPE NOT IN('Off_net SMART HD', 'Не подключен', 'Off_net SMART HD, 0к, РБ') ORDER BY CNT DESC;";
        $queryArrayKeys = array('CITY','STREET','HOUSE','HOUSE_ID','CNT');
        //echo $query.'<hr>';
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $sumObjectsArray = $retuenedArray;
        //print_r($sumObjectsArray);
        $arr_response = array('response' => array());
        foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
          $arr = array(
            'city' => $retuenedArray[$sumObjectsArrayKey]['CITY'],
            'street' => $retuenedArray[$sumObjectsArrayKey]['STREET'],
            'house' => $retuenedArray[$sumObjectsArrayKey]['HOUSE'],
            'cubic_house_id' => $retuenedArray[$sumObjectsArrayKey]['HOUSE_ID'],
            'flats' => $retuenedArray[$sumObjectsArrayKey]['CNT']
          );
          array_push($arr_response['response'], $arr ); 
        }
        print json_encode($arr_response);
      }
    } 
  }
}  
?>

