<?php
ini_set('display_errors', 1);


$city_request = "SELECT city_eng FROM public.links WHERE links IS NOT NULL";
$city_array = array();
           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           //$credentials = "user=postgres password=Xjrjkzlrf30";
           $credentials = "user=simpleuser password=simplepassword";
           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
             //echo "Opened database successfully\n";
             	  $ret_city_array = pg_query($db, $city_request);
	                      if($ret_city_array) {
	                         while ($row = pg_fetch_row($ret_city_array))  {
			             //print_r($row);
                                $city_array [] = $row[0];
	                         }
                           print_r($city_array);
	                   }
                     foreach ($city_array as $city_key => $selectedCity) {
                        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/cubic/_buildings/".$selectedCity."_buildings.csv'";
                        $city_buildings_data_update = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),REGION character varying(100),DISTR_NEW character varying(100),STREET character varying(100),HOUSE character varying(100),COMM text,CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100),LNAME character varying(100),LADRESS character varying(100),HPNAME character varying(100),HPADRESS character varying(100),HPCODE character varying(100),FREQ character varying(100),DATE_BUILDING character varying(100),DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100),DIGITAL_SEGMENT character varying(100),DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100),BOX_TYPE character varying(100),HOUSE_ID character varying(100),SECTOR_CNT character varying(100),CNT character varying(100),PARNET character varying(200),SERV_PARNET character varying(200),NETTYPE character varying(100),CNT_ATV character varying(100),CNT_VBB character varying(100),CNT_ETH character varying(100),CNT_DOCSIS character varying(100),CNT_KTV character varying(100),CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100),MAX_SPEED_DOCSIS character varying(100),REPORT_DATE character varying(100)); select copy_for_testuser('temp(CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE,CNT_ATV,CNT_VBB,CNT_ETH,CNT_DOCSIS,CNT_KTV,CNT_ACTIVE_CONTR,MAX_SPEED_ETHERNET,MAX_SPEED_DOCSIS,REPORT_DATE)', ".$linkStorage.", ',' , 'UTF-8') ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = temp.CITY, cubic_region = temp.REGION, cubic_distr_new = temp.DISTR_NEW, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_subdep = temp.SUBDEP, cubic_uso = temp.USO, cubic_lname = temp.LNAME, cubic_ladress = temp.LADRESS, cubic_hpname = temp.HPNAME, cubic_hpadress = temp.HPADRESS, cubic_network_type = temp.NETTYPE, cubic_freq = temp.FREQ, cubic_house_type = temp.HOUSE_TYPE, cubic_csd = temp.CSD, cubic_cnt = temp.CNT, cubic_comm = temp.COMM, cubic_cnt_vbb = temp.CNT_VBB, cubic_cnt_eth = temp.CNT_ETH, cubic_cnt_docsis = temp.CNT_DOCSIS, cubic_cnt_ktv = temp.CNT_KTV, cubic_cnt_atv = temp.CNT_ATV, cubic_cnt_active_contr = temp.CNT_ACTIVE_CONTR, cubic_date_building = temp.DATE_BUILDING, cubic_date_building_eth = temp.DATE_BUILDING_ETH, cubic_date_ct = temp.DATE_CT, cubic_segment = temp.SEGMENT, cubic_digital_segment = temp.DIGITAL_SEGMENT, cubic_digital_stage = temp.DIGITAL_STAGE, cubic_digital_date = temp.DIGITAL_DATE, cubic_box_type = temp.BOX_TYPE, cubic_house_id = temp.HOUSE_ID, cubic_parnet = temp.PARNET, cubic_serv_parnet = temp.SERV_PARNET, cubic_sector_cnt = temp.SECTOR_CNT, cubic_hpcode = temp.HPCODE, cubic_max_speed_ethernet = temp.MAX_SPEED_ETHERNET, cubic_max_speed_docsis = temp.MAX_SPEED_DOCSIS, upload_time=now() FROM  temp WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id = temp.HOUSE_ID; DROP TABLE temp;";
                        $ret_city_array = pg_query($db, $city_buildings_data_update);
                        $net_type_bug_fix = "UPDATE " . $selectedCity.".".$selectedCity."_buildings SET cubic_network_type = CASE "." WHEN cubic_network_type LIKE '%VPTV,Off_net SMART HD%' THEN  'Off_net SMART HD, VPTV' "." WHEN cubic_network_type LIKE '%VPTV,Off_net SMART HD,Индивидуальное подключение%' THEN  'Off_net SMART HD, VPTV, Индивидуальное подключение' "." WHEN cubic_network_type LIKE '%VPTV,Off_net SMART HD,Индивидуальное подключение%' THEN  'EuroDOCSIS 3, Off_net SMART HD, VPTV, Индивидуальное подключение' " ." WHEN cubic_network_type LIKE '%VPTV,SMART HD,EuroDOCSIS 3%' THEN  'EuroDOCSIS 3, SMART HD, VPTV' " ." WHEN cubic_network_type LIKE '%VPTV,SMART HD,Индивидуальное подключение,EuroDOCSIS 3%' THEN  'EuroDOCSIS 3, SMART HD, VPTV, Индивидуальное подключение' " ." WHEN cubic_network_type LIKE '%VPTV,Индивидуальное подключение,EuroDOCSIS 3' THEN  'EuroDOCSIS 3, VPTV, Индивидуальное подключение' " ." ELSE cubic_network_type END;";
                        $ret_net_type_bug_fix = pg_query($db, $net_type_bug_fix);
                     }
	}
  echo 'that is all';
  pg_close($db); // Closing Connection
?>