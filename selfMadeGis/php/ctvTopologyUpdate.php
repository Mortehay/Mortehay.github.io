<?php
//ini_set('display_errors', 1);
//ini_set('max_execution_time', 0);
          
	 $selectedCity= $_POST['ctv_city_eng'];
        
      $linkStorage = "'/tmp/".$selectedCity."_ctv_topology.csv'";
      $dir = sys_get_temp_dir();
      $files = scandir($dir);  
	
           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=simpleuser password=simplepassword";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
	
            //-----------------------------------------------------------------
            if ($files) {
              foreach($files as $file) {
                $str_file = (string)$file;
                if ($str_file !== '.' && $str_file !== '..') {
                      //print_r($str_file);
                      if ($str_file == $selectedCity."_ctv_topology.csv") {
                        //print_r($str_file);
                        $data_upload = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),OU_CODE character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),HOUSE_ID character varying(100)); select copy_for_testuser('temp(CITY, STREET, HOUSE, FLAT, CODE, NAME, PGS_ADDR, OU_OP_ADDR, OU_CODE, DATE_REG, COMENT, UNAME, NET_TYPE, HOUSE_ID)', ".$linkStorage.", ';', 'windows-1251') ; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = temp.COMENT, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE " . $selectedCity.".".$selectedCity."_ctv_topology.cubic_code = temp.CODE; SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL);";
                        $data_upload_query = pg_query($db, $data_upload);
                       if($data_upload_query) {
                        $arr_response = array('response' => array());
                         while ($row = pg_fetch_row($data_upload_query) )  {
 
                          $arr = array(
                              'cubic_city' => $row[0],
                              'cubic_street' => $row[1],
                              'cubic_house' => $row[2],
                              'cubic_flat' => $row[3],
                              'cubic_code' => $row[4],
                              'cubic_name' => $row[5],
                              'cubic_pgs_addr' => $row[6],
                              'cubic_ou_op_addr' => $row[7],
                              'cubic_ou_code' => $row[8],
                              'cubic_date_reg' => $row[9],
                              'cubic_coment' => $row[10],
                              'cubic_uname' => $row[11],
                              'cubic_net_type' => $row[12],
                              'cubic_house_id' => $row[13]

                            );
                          //print_r( $arr);
                          array_push($arr_response['response'], $arr );
                          //array_push($arr_response, $arr);
                        }

                      } else {
                        echo 'no new ctv equipment';
                      }
                       
                      }
                  }
                }
              }

            //-----------------------------------------------------------------
            $equipment_geom_update ="UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Магистральный распределительный узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Магістральний оптичний вузол' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Оптический узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Оптичний приймач' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Передатчик оптический' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Порт ОК' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Домовой узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Ответвитель магистральный' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Распределительный стояк' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Магистральный узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Субмагистральный узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Кросс-муфта' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
                $equipment_geometry_update_query =pg_query($db, $equipment_geom_update);
                //print( $equipment_geom_update);
            //-----------------------------------------------------------------
          	$sql = "CREATE TEMP TABLE tmp AS SELECT * FROM ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology);	UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET mother_equipment_geom = tmp.equipment_geom FROM tmp WHERE ".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;	 UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE ".$selectedCity."_ctv_topology.mother_equipment_geom IS NOT null AND ".$selectedCity."_ctv_topology.equipment_geom IS NOT NULL;";
          	$ret = pg_query($db, $sql);
                //print( $sql);
}
print json_encode($arr_response);
pg_close($db); // Closing Connection
	
?>

