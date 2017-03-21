<?php

//ini_set('display_errors', 1);
include('classFunctionStorage.php');
$newDBrequest = new dbConnSetClass;
$query = "SELECT city_eng FROM public.links WHERE links IS NOT NULL;";
$queryArrayKeys = array('city_eng');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$city_array = $retuenedArray;
//echo '<hr>';
//  echo $query;
//echo '<hr>';
//  print_r($city_array);
//echo '<hr>';
foreach ($city_array as $city_key => $selectedCity) {
  $selectedCity = $selectedCity['city_eng'];
  $newDBrequest = new dbConnSetClass;
  $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/cubic/_buildings/".$selectedCity."_buildings.csv'";
  $query = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),REGION character varying(100),DISTR_NEW character varying(100),STREET character varying(100),HOUSE character varying(100),COMM text,CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100),LNAME character varying(100),LADRESS character varying(100),HPNAME character varying(100),HPADRESS character varying(100),HPCODE character varying(100),FREQ character varying(100),DATE_BUILDING character varying(100),DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100),DIGITAL_SEGMENT character varying(100),DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100),BOX_TYPE character varying(100),HOUSE_ID character varying(100),SECTOR_CNT character varying(100),CNT character varying(100),PARNET character varying(200),SERV_PARNET character varying(200),NETTYPE character varying(100),CNT_ATV character varying(100),CNT_VBB character varying(100),CNT_ETH character varying(100),CNT_DOCSIS character varying(100),CNT_KTV character varying(100),CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100),MAX_SPEED_DOCSIS character varying(100),REPORT_DATE character varying(100)); select copy_for_testuser('temp(CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE,CNT_ATV,CNT_VBB,CNT_ETH,CNT_DOCSIS,CNT_KTV,CNT_ACTIVE_CONTR,MAX_SPEED_ETHERNET,MAX_SPEED_DOCSIS,REPORT_DATE)', ".$linkStorage.", ',' , 'UTF-8') ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = temp.CITY, cubic_region = temp.REGION, cubic_distr_new = temp.DISTR_NEW, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_subdep = temp.SUBDEP, cubic_uso = temp.USO, cubic_lname = temp.LNAME, cubic_ladress = temp.LADRESS, cubic_hpname = temp.HPNAME, cubic_hpadress = temp.HPADRESS, cubic_network_type = temp.NETTYPE, cubic_freq = temp.FREQ, cubic_house_type = temp.HOUSE_TYPE, cubic_csd = temp.CSD, cubic_cnt = temp.CNT, cubic_comm = temp.COMM, cubic_cnt_vbb = temp.CNT_VBB, cubic_cnt_eth = temp.CNT_ETH, cubic_cnt_docsis = temp.CNT_DOCSIS, cubic_cnt_ktv = temp.CNT_KTV, cubic_cnt_atv = temp.CNT_ATV, cubic_cnt_active_contr = temp.CNT_ACTIVE_CONTR, cubic_date_building = temp.DATE_BUILDING, cubic_date_building_eth = temp.DATE_BUILDING_ETH, cubic_date_ct = temp.DATE_CT, cubic_segment = temp.SEGMENT, cubic_digital_segment = temp.DIGITAL_SEGMENT, cubic_digital_stage = temp.DIGITAL_STAGE, cubic_digital_date = temp.DIGITAL_DATE, cubic_box_type = temp.BOX_TYPE, cubic_house_id = temp.HOUSE_ID, cubic_parnet = temp.PARNET, cubic_serv_parnet = temp.SERV_PARNET, cubic_sector_cnt = temp.SECTOR_CNT, cubic_hpcode = temp.HPCODE, cubic_max_speed_ethernet = temp.MAX_SPEED_ETHERNET, cubic_max_speed_docsis = temp.MAX_SPEED_DOCSIS, upload_time=now() FROM  temp WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id = temp.HOUSE_ID; DROP TABLE temp;";
  $queryArrayKeys = false;
  $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
  //echo '<hr>';
  //echo $query;
}

?>