<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['city_building_data_eng'];  


      if (file_exists("/tmp/".$selectedCity."_buildings.csv'")) {
        $linkStorage = "'/tmp/".$selectedCity."_buildings.csv''";
        $dir = sys_get_temp_dir();
        $files = scandir($dir);  
      } else {
        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_buildings.csv'" ;
        $dir = "/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/";
        $files = scandir($dir);
      }
      //echo $linkStorage;
      //echo '<hr>'. file_exists($linkStorage);

           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=simpleuser password=simplepassword";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                //echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
	}

    if ($files) {
    foreach($files as $file) {
      $str_file = (string)$file;
      if ($str_file !== '.' && $str_file !== '..') {
            //print_r($str_file);
            if ($str_file == $selectedCity."_buildings.csv") {
                //-------------------------------------------------------------------------------------------------------------------
                 $sql = "CREATE TEMP TABLE temp(id serial, CITY character varying(100), REGION character varying(100), DISTR_NEW character varying(100), STREET character varying(100), HOUSE character varying(100), SUBDEP character varying(100), USO character varying(100), LNAME character varying(100), LADRESS character varying(100), HPNAME character varying(100), HPADRESS character varying(100), NETTYPE character varying(100), FREQ character varying(100), HOUSE_TYPE character varying(100), CSD character varying(100), CNT character varying(100), COMM text, CNT_VBB character varying(100), CNT_ETH character varying(100), CNT_DOCSIS character varying(100), CNT_KTV character varying(100), CNT_ATV character varying(100), CNT_ACTIVE_CONTR character varying(100), DATE_BUILDING character varying(100), DATE_BUILDING_ETH character varying(100), DATE_CT character varying(100), SEGMENT character varying(100), DIGITAL_SEGMENT character varying(100), DIGITAL_STAGE character varying(100), DIGITAL_DATE character varying(100), BOX_TYPE character varying(100), HOUSE_ID character varying(100), PARNET character varying(200), SERV_PARNET character varying(200), SECTOR_CNT character varying(100), HPCODE character varying(100), MAX_SPEED_ETHERNET character varying(100), MAX_SPEED_DOCSIS character varying(100)); select copy_for_testuser('temp(CITY, REGION, DISTR_NEW, STREET, HOUSE,  SUBDEP, USO, LNAME, LADRESS, HPNAME, HPADRESS, NETTYPE, FREQ, HOUSE_TYPE, CSD, CNT, COMM, CNT_VBB, CNT_ETH, CNT_DOCSIS, CNT_KTV, CNT_ATV, CNT_ACTIVE_CONTR, DATE_BUILDING, DATE_BUILDING_ETH, DATE_CT, SEGMENT, DIGITAL_SEGMENT, DIGITAL_STAGE, DIGITAL_DATE, BOX_TYPE, HOUSE_ID, PARNET, SERV_PARNET, SECTOR_CNT, HPCODE, MAX_SPEED_ETHERNET, MAX_SPEED_DOCSIS )', ".$linkStorage.", ';', 'windows-1251') ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = temp.CITY, cubic_region = temp.REGION, cubic_distr_new = temp.DISTR_NEW, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_subdep = temp.SUBDEP, cubic_uso = temp.USO, cubic_lname = temp.LNAME, cubic_ladress = temp.LADRESS, cubic_hpname = temp.HPNAME, cubic_hpadress = temp.HPADRESS, cubic_network_type = temp.NETTYPE, cubic_freq = temp.FREQ, cubic_house_type = temp.HOUSE_TYPE, cubic_csd = temp.CSD, cubic_cnt = temp.CNT, cubic_comm = temp.COMM, cubic_cnt_vbb = temp.CNT_VBB, cubic_cnt_eth = temp.CNT_ETH, cubic_cnt_docsis = temp.CNT_DOCSIS, cubic_cnt_ktv = temp.CNT_KTV, cubic_cnt_atv = temp.CNT_ATV, cubic_cnt_active_contr = temp.CNT_ACTIVE_CONTR, cubic_date_building = temp.DATE_BUILDING, cubic_date_building_eth = temp.DATE_BUILDING_ETH, cubic_date_ct = temp.DATE_CT, cubic_segment = temp.SEGMENT, cubic_digital_segment = temp.DIGITAL_SEGMENT, cubic_digital_stage = temp.DIGITAL_STAGE, cubic_digital_date = temp.DIGITAL_DATE, cubic_box_type = temp.BOX_TYPE, cubic_house_id = temp.HOUSE_ID, cubic_parnet = temp.PARNET, cubic_serv_parnet = temp.SERV_PARNET, cubic_sector_cnt = temp.SECTOR_CNT, cubic_hpcode = temp.HPCODE, cubic_max_speed_ethernet = temp.MAX_SPEED_ETHERNET, cubic_max_speed_docsis = temp.MAX_SPEED_DOCSIS FROM  temp WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id = temp.HOUSE_ID; DROP TABLE temp;";
                  $ret = pg_query($db, $sql);
                  //-------------------------------------------------------------------------------------------------------------------
                  $erase = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = NULL, cubic_region = NULL, cubic_distr_new = NULL, cubic_street = NULL, cubic_house = NULL, cubic_subdep = NULL, cubic_uso = NULL, cubic_lname = NULL, cubic_ladress = NULL, cubic_hpname = NULL, cubic_hpadress = NULL, cubic_network_type =NULL, cubic_freq = NULL, cubic_house_type = NULL, cubic_csd = NULL, cubic_cnt = NULL, cubic_comm = NULL, cubic_cnt_vbb = NULL, cubic_cnt_eth = NULL, cubic_cnt_docsis = NULL, cubic_cnt_ktv = NULL, cubic_cnt_atv = NULL, cubic_cnt_active_contr = NULL, cubic_date_building = NULL, cubic_date_building_eth = NULL, cubic_date_ct = NULL, cubic_segment = NULL, cubic_digital_segment = NULL, cubic_digital_stage = NULL, cubic_digital_date = NULL, cubic_box_type = NULL, cubic_parnet = NULL, cubic_serv_parnet = NULL, cubic_sector_cnt = NULL, cubic_hpcode = NULL, cubic_max_speed_ethernet = NULL, cubic_max_speed_docsis = NULL  WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id IS NULL; ";
                  $erase_query = pg_query($db, $erase);
                  //-------------------------------------------------------------------------------------------------------------------
                  $building_points_update = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_firstpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),1), 32636) WHERE building_geom_firstpoint IS NULL ;  UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_secondpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),2), 32636) WHERE building_geom_secondpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_thirdpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),3), 32636) WHERE building_geom_thirdpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_fourthpoint  = ST_SetSRID(ST_PointN(ST_Boundary(building_geom),4), 32636) WHERE building_geom_fourthpoint IS NULL ;";
                  $update_building_points_query = pg_query($db, $building_points_update);
                  //------------------------------------------------------------------------------------------------------------------- for multiline
                   $building_points_update_multiline =  "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_firstpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_firstpoint IS NULL ;  UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_secondpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_secondpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_thirdpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_thirdpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_fourthpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_fourthpoint IS NULL ;";
                  $update_building_points_query_multiline = pg_query($db, $building_points_update_multiline);

                  //-------------------------------------------------------------------------------------------------------------------
                  $response = "CREATE TEMP TABLE temp(id serial, CITY character varying(100), REGION character varying(100), DISTR_NEW character varying(100), STREET character varying(100), HOUSE character varying(100), SUBDEP character varying(100), USO character varying(100), LNAME character varying(100), LADRESS character varying(100), HPNAME character varying(100), HPADRESS character varying(100), NETTYPE character varying(100), FREQ character varying(100), HOUSE_TYPE character varying(100), CSD character varying(100), CNT character varying(100), COMM text, CNT_VBB character varying(100), CNT_ETH character varying(100), CNT_DOCSIS character varying(100), CNT_KTV character varying(100), CNT_ATV character varying(100), CNT_ACTIVE_CONTR character varying(100), DATE_BUILDING character varying(100), DATE_BUILDING_ETH character varying(100), DATE_CT character varying(100), SEGMENT character varying(100), DIGITAL_SEGMENT character varying(100), DIGITAL_STAGE character varying(100), DIGITAL_DATE character varying(100), BOX_TYPE character varying(100), HOUSE_ID character varying(100), PARNET character varying(200), SERV_PARNET character varying(200), SECTOR_CNT character varying(100), HPCODE character varying(100), MAX_SPEED_ETHERNET character varying(100), MAX_SPEED_DOCSIS character varying(100)); select copy_for_testuser('temp(CITY, REGION, DISTR_NEW, STREET, HOUSE,  SUBDEP, USO, LNAME, LADRESS, HPNAME, HPADRESS, NETTYPE, FREQ, HOUSE_TYPE, CSD, CNT, COMM, CNT_VBB, CNT_ETH, CNT_DOCSIS, CNT_KTV, CNT_ATV, CNT_ACTIVE_CONTR, DATE_BUILDING, DATE_BUILDING_ETH, DATE_CT, SEGMENT, DIGITAL_SEGMENT, DIGITAL_STAGE, DIGITAL_DATE, BOX_TYPE, HOUSE_ID, PARNET, SERV_PARNET, SECTOR_CNT, HPCODE, MAX_SPEED_ETHERNET, MAX_SPEED_DOCSIS )', ".$linkStorage.", ';', 'windows-1251') ; SELECT CITY, STREET, HOUSE, HOUSE_ID, CNT FROM temp WHERE HOUSE_ID  NOT IN(SELECT cubic_house_id FROM ".$selectedCity.".".$selectedCity."_buildings WHERE cubic_house_id IS NOT NULL) IS TRUE AND NETTYPE NOT IN('Off_net SMART HD', 'Не подключен', 'Off_net SMART HD, 0к, РБ') ORDER BY CNT DESC;";
                  $ret_response = pg_query($db, $response);
                  $arr_response = array('response' => array());
                  //$arr_response = array();
                  //-------------------------------------------------------------------------------------------------------------------
                      if($ret_response) {

                         while ($row = pg_fetch_row($ret_response))  {

                          $arr = array(
                              'city' => $row[0],
                              'street' => $row[1],
                              'house' => $row[2],
                              'cubic_house_id' => $row[3],
                              'flats' => $row[4]
                            );
                          //print_r( $arr);
                          array_push($arr_response['response'], $arr );
                          //array_push($arr_response, $arr);
                        }

                      } else {
                        echo 'no new buildings';
                      }
                   
                  } else {
                    //echo ' error cannot update data ';
                  }
            }
          } 


        }
        //$response = "select cubic_city, cubic_street, cubic_house, cubic_house_id from kiev.kiev_buildings where cubic_street = 'Амосова Миколи'";

                  

       print json_encode($arr_response);

	pg_close($db); // Closing Connection
	//print( $sql); 
  
?>

