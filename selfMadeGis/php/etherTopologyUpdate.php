<?php
ini_set('display_errors', 1);

          
	//$selectedCity= $_POST['ether_city_eng'];  
      if ($_POST['ether_city_eng']) {
        $selectedCity= $_POST['ether_city_eng'];
      } else {
        $selectedCity = $_REQUEST['selectedCity'];
      } 

      if (file_exists("/tmp/".$selectedCity."_ethernet_topology.csv")) {
        $linkStorage = "'/tmp/".$selectedCity."_ethernet_topology.csv'";
        $dir = sys_get_temp_dir();
        $files = scandir($dir);  
      } else {
        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_ethernet_topology.csv'" ;
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
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
             if ($files) {
              foreach($files as $file) {
                $str_file = (string)$file;
                if ($str_file !== '.' && $str_file !== '..') {
                     //print_r($str_file);
                      if ($str_file == $selectedCity."_ctv_topology.csv") {
                        //print_r($str_file);

                        $data_upload = "CREATE TEMP TABLE temp(id serial, MAC_ADDRESS character varying(100),IP_ADDRESS character varying(100),SERIAL_NUMBER character varying(100),HOSTNAME character varying(100),DEV_FULL_NAME character varying(100),VENDOR_MODEL character varying(100),SW_MODEL character varying(100),SW_ROLE character varying(100),HOUSE_ID character varying(100),DOORWAY character varying(100),LOCATION character varying(100),FLOOR character varying(100),SW_MON_TYPE character varying(100),SW_INV_STATE character varying(100),VLAN character varying(100),DATE_CREATE character varying(100),DATE_CHANGE character varying(100),IS_CONTROL character varying(100),IS_OPT82 character varying(100),COMMUNITY character varying(100),PARENT_MAC character varying(100),PARENT_PORT character varying(100),CHILD_MAC character varying(100),CHILD_PORT character varying(100),PORT_NUMBER character varying(100),PORT_STATE character varying(100),CONTRACT_CNT character varying(100),CONTRACT_ACTIVE_CNT character varying(100),GUEST_VLAN character varying(100)); select copy_for_testuser('temp(MAC_ADDRESS,IP_ADDRESS,SERIAL_NUMBER,HOSTNAME,DEV_FULL_NAME,VENDOR_MODEL,SW_MODEL,SW_ROLE,HOUSE_ID,DOORWAY,LOCATION,FLOOR,SW_MON_TYPE,SW_INV_STATE,VLAN,DATE_CREATE,DATE_CHANGE,IS_CONTROL,IS_OPT82,COMMUNITY,PARENT_MAC,PARENT_PORT,CHILD_MAC,CHILD_PORT,PORT_NUMBER,PORT_STATE,CONTRACT_CNT,CONTRACT_ACTIVE_CNT,GUEST_VLAN)', ".$linkStorage.", ';', 'windows-1251') ; UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_mac_address = temp.MAC_ADDRESS,cubic_ip_address = temp.IP_ADDRESS,cubic_hostname = temp.HOSTNAME,cubic_switch_model = temp.SW_MODEL,cubic_switch_role = temp.SW_ROLE,cubic_house_id = temp.HOUSE_ID,cubic_house_entrance_num = temp.DOORWAY,cubic_monitoring_method = temp.SW_MON_TYPE,cubic_inventary_state = temp.SW_INV_STATE,cubic_vlan = temp.VLAN, cubic_parent_down_port = temp.PARENT_PORT,cubic_parent_mac_address = temp.PARENT_MAC,cubic_up_port = temp.PORT_NUMBER,cubic_rgu = temp.CONTRACT_CNT FROM  temp WHERE " . $selectedCity.".".$selectedCity."_switches.cubic_mac_address = temp.MAC_ADDRESS; SELECT cubic_street, cubic_house, MAC_ADDRESS,IP_ADDRESS,SERIAL_NUMBER,HOSTNAME,SW_MODEL,HOUSE_ID,DOORWAY,LOCATION,FLOOR,SW_INV_STATE,DATE_CREATE,DATE_CHANGE FROM temp LEFT JOIN ". $selectedCity.".".$selectedCity."_buildings ON temp.HOUSE_ID = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id WHERE MAC_ADDRESS NOT IN(SELECT cubic_mac_address FROM ". $selectedCity.".".$selectedCity."_switches WHERE cubic_mac_address IS NOT NULL);";
                        $data_upload_query = pg_query($db, $data_upload);

                       if($data_upload_query) {
                        $arr_response = array('response' => array());
                         while ($row = pg_fetch_row($data_upload_query) )  {
 
                          $arr = array(
                              'cubic_street,' => $row[0],
                              'cubic_house' => $row[1],
                              'DOORWAY' => $row[8],
                              'FLOOR' => $row[10],
                              'LOCATION' => $row[9],
                              'HOUSE_ID' => $row[7],
                              'MAC_ADDRESS' => $row[2],
                              'IP_ADDRESS' => $row[3],
                              'SERIAL_NUMBER' => $row[4],
                              'HOSTNAME' => $row[5],
                              'SW_MODEL' => $row[6],
                              'SW_INV_STATE' => $row[11],
                              'DATE_CREATE' => $row[12],
                              'DATE_CHANGE' => $row[13],

                            );
                          //print_r( $arr);
                          array_push($arr_response['response'], $arr );
                          //array_push($arr_response, $arr);
                        }
                      
                      } else {  
                        $arr[0] =  'no new ethernet equipment';
                        array_push($arr_response['response'], $arr );
                      }
                        //echo 'no new ctv equipment'; 
                       
                      }
                  }
                }
              }
               //-----------------------------------------------------------------
            $equipment_geom_update ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET switches_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_switches.switches_geom IS NULL AND ".$selectedCity.".".$selectedCity."_switches.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
                $equipment_geometry_update_query =pg_query($db, $equipment_geom_update);

              $sql = "CREATE TEMP TABLE tmp AS SELECT cubic_mac_address, cubic_switch_role, cubic_switch_model,  switches_geom FROM ".$selectedCity.".".$selectedCity."_switches where cubic_mac_address IN (SELECT cubic_mac_address FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_mac_address IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_switches SET parent_switches_geom = tmp.switches_geom, cubic_parent_switch_role = tmp.cubic_switch_role, cubic_parent_switch_model = tmp.cubic_switch_model FROM tmp WHERE ".$selectedCity."_switches.cubic_parent_mac_address = tmp.cubic_mac_address; DROP TABLE tmp;   UPDATE ".$selectedCity.".".$selectedCity."_switches SET topology_line_geom = ST_MakeLine(parent_switches_geom, switches_geom) WHERE ".$selectedCity."_switches.parent_switches_geom IS NOT null AND ".$selectedCity."_switches.switches_geom IS NOT NULL;";
              $ret = pg_query($db, $sql);
	}
      print json_encode($arr_response);
	pg_close($db); // Closing Connection
	//print( $sql);
?>

