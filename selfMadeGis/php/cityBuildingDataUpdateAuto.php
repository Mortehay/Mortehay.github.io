<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['city_building_data_eng_auto']) {
  $selectedCity= $_POST['city_building_data_eng_auto'];
} else {
  $selectedCity = $_REQUEST['city_building_data_eng_auto'];
}  

  $fileExtention ="_buildings.csv";
  $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/cubic/_buildings/".$selectedCity."_buildings.csv'" ;
  $dir = "/var/www/QGIS-Web-Client-master/site/csv/cubic/_buildings/";
  $files = scandir($dir);
  //print_r($files);
  //print_r($linkStorage);
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$fileExtention) {
        $newDBrequest = new dbConnSetClass;
        $query = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),REGION character varying(100),DISTR_NEW character varying(100),STREET character varying(100),HOUSE character varying(100),COMM text,CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100),LNAME character varying(100),LADRESS character varying(100),HPNAME character varying(100),HPADRESS character varying(100),HPCODE character varying(100),FREQ character varying(100),DATE_BUILDING character varying(100),DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100),DIGITAL_SEGMENT character varying(100),DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100),BOX_TYPE character varying(100),HOUSE_ID character varying(100),SECTOR_CNT character varying(100),CNT character varying(100),PARNET character varying(200),SERV_PARNET character varying(200),NETTYPE character varying(100),CNT_ATV character varying(100),CNT_VBB character varying(100),CNT_ETH character varying(100),CNT_DOCSIS character varying(100),CNT_KTV character varying(100),CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100),MAX_SPEED_DOCSIS character varying(100),REPORT_DATE character varying(100)); select copy_for_testuser('temp(CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE,CNT_ATV,CNT_VBB,CNT_ETH,CNT_DOCSIS,CNT_KTV,CNT_ACTIVE_CONTR,MAX_SPEED_ETHERNET,MAX_SPEED_DOCSIS,REPORT_DATE)', ".$linkStorage.", ',' , 'UTF-8') ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = temp.CITY, cubic_region = temp.REGION, cubic_distr_new = temp.DISTR_NEW, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_subdep = temp.SUBDEP, cubic_uso = temp.USO, cubic_lname = temp.LNAME, cubic_ladress = temp.LADRESS, cubic_hpname = temp.HPNAME, cubic_hpadress = temp.HPADRESS, cubic_network_type = temp.NETTYPE, cubic_freq = temp.FREQ, cubic_house_type = temp.HOUSE_TYPE, cubic_csd = temp.CSD, cubic_cnt = temp.CNT, cubic_comm = temp.COMM, cubic_cnt_vbb = temp.CNT_VBB, cubic_cnt_eth = temp.CNT_ETH, cubic_cnt_docsis = temp.CNT_DOCSIS, cubic_cnt_ktv = temp.CNT_KTV, cubic_cnt_atv = temp.CNT_ATV, cubic_cnt_active_contr = temp.CNT_ACTIVE_CONTR, cubic_date_building = temp.DATE_BUILDING, cubic_date_building_eth = temp.DATE_BUILDING_ETH, cubic_date_ct = temp.DATE_CT, cubic_segment = temp.SEGMENT, cubic_digital_segment = temp.DIGITAL_SEGMENT, cubic_digital_stage = temp.DIGITAL_STAGE, cubic_digital_date = temp.DIGITAL_DATE, cubic_box_type = temp.BOX_TYPE, cubic_house_id = temp.HOUSE_ID, cubic_parnet = temp.PARNET, cubic_serv_parnet = temp.SERV_PARNET, cubic_sector_cnt = temp.SECTOR_CNT, cubic_hpcode = temp.HPCODE, cubic_max_speed_ethernet = temp.MAX_SPEED_ETHERNET, cubic_max_speed_docsis = temp.MAX_SPEED_DOCSIS, upload_time=now() FROM  temp WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id = temp.HOUSE_ID; DROP TABLE temp;";
        $queryArrayKeys = false;
        //echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = NULL, cubic_region = NULL, cubic_distr_new = NULL, cubic_street = NULL, cubic_house = NULL, cubic_subdep = NULL, cubic_uso = NULL, cubic_lname = NULL, cubic_ladress = NULL, cubic_hpname = NULL, cubic_hpadress = NULL, cubic_network_type =NULL, cubic_freq = NULL, cubic_house_type = NULL, cubic_csd = NULL, cubic_cnt = NULL, cubic_comm = NULL, cubic_cnt_vbb = NULL, cubic_cnt_eth = NULL, cubic_cnt_docsis = NULL, cubic_cnt_ktv = NULL, cubic_cnt_atv = NULL, cubic_cnt_active_contr = NULL, cubic_date_building = NULL, cubic_date_building_eth = NULL, cubic_date_ct = NULL, cubic_segment = NULL, cubic_digital_segment = NULL, cubic_digital_stage = NULL, cubic_digital_date = NULL, cubic_box_type = NULL, cubic_parnet = NULL, cubic_serv_parnet = NULL, cubic_sector_cnt = NULL, cubic_hpcode = NULL, cubic_max_speed_ethernet = NULL, cubic_max_speed_docsis = NULL  WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id IS NULL;";
        $queryArrayKeys = false;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_firstpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),1), 32636) WHERE building_geom_firstpoint IS NULL ;  UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_secondpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),2), 32636) WHERE building_geom_secondpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_thirdpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),3), 32636) WHERE building_geom_thirdpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_fourthpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),4), 32636) WHERE building_geom_fourthpoint IS NULL ;";
        $queryArrayKeys = false;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_firstpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_firstpoint IS NULL ;  UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_secondpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_secondpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_thirdpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_thirdpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_fourthpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_fourthpoint IS NULL ;";
        $queryArrayKeys = false;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),REGION character varying(100),DISTR_NEW character varying(100),STREET character varying(100),HOUSE character varying(100),COMM text,CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100),LNAME character varying(100),LADRESS character varying(100),HPNAME character varying(100),HPADRESS character varying(100),HPCODE character varying(100),FREQ character varying(100),DATE_BUILDING character varying(100),DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100),DIGITAL_SEGMENT character varying(100),DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100),BOX_TYPE character varying(100),HOUSE_ID character varying(100),SECTOR_CNT character varying(100),CNT character varying(100),PARNET character varying(200),SERV_PARNET character varying(200),NETTYPE character varying(100),CNT_ATV character varying(100),CNT_VBB character varying(100),CNT_ETH character varying(100),CNT_DOCSIS character varying(100),CNT_KTV character varying(100),CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100),MAX_SPEED_DOCSIS character varying(100),REPORT_DATE character varying(100)); select copy_for_testuser('temp(CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE,CNT_ATV,CNT_VBB,CNT_ETH,CNT_DOCSIS,CNT_KTV,CNT_ACTIVE_CONTR,MAX_SPEED_ETHERNET,MAX_SPEED_DOCSIS,REPORT_DATE)', ".$linkStorage.", ',' , 'UTF-8') ;  SELECT CITY, STREET, HOUSE, HOUSE_ID, CNT FROM temp WHERE HOUSE_ID  NOT IN(SELECT cubic_house_id FROM ".$selectedCity.".".$selectedCity."_buildings WHERE cubic_house_id IS NOT NULL) IS TRUE AND NETTYPE NOT IN('Off_net SMART HD', 'Не подключен', 'Off_net SMART HD, 0к, РБ') ORDER BY CNT DESC;";
        $queryArrayKeys = array('CITY','STREET','HOUSE','HOUSE_ID','CNT');
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

