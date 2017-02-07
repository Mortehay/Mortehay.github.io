<?php
//ini_set('display_errors', 1);
//ini_set('max_execution_time', 0);
       function groupSelect($cubic_name){
            $group_value = array(0, '#DC143C',null,null);
            if ($cubic_name == 'Оптический узел') { $group_value = array( 1, '#ff9900', 60, 'nod');}
            if ($cubic_name == 'Оптичний приймач') { $group_value = array(2, '#663300', 60, 'op');}
            if ($cubic_name == 'Магістральний оптичний вузол') { $group_value = array( 3, '#3333cc', 90, 'mnod');}
            if ($cubic_name == 'Передатчик оптический') { $group_value = array( 4, '#333399', 90, 'ot');}
            if ($cubic_name == 'Магистральный распределительный узел') { $group_value = array( 5, '#ff0000', 80, 'mdod');}
            if ($cubic_name == 'Кросс-муфта') { $group_value = array( 6, '#ff0066', 60, 'cc');}
        return $group_value;
      }
      function topologyDirCreate($description, $city){
              
                $dirPath = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$city.'/topology/'.$description['cubic_name'].'/'.$description['cubic_code'];
                if (!file_exists($dirPath )) {
                  $oldmask = umask(0);
                      mkdir($dirPath , 0777, true);
                      umask($oldmask);
                }
        //echo $dirPath;
        return true;

      }
	 //$selectedCity= $_POST['ctv_city_eng'];
       if ($_POST['ctv_city_eng']) {
        $selectedCity= $_POST['ctv_city_eng'];
      } else {
        $selectedCity = $_REQUEST["selectedCity"];
      } 
      
       if (file_exists("/tmp/".$selectedCity."_ctv_topology.csv")) {
        $linkStorage = "'/tmp/".$selectedCity."_ctv_topology.csv'";
        $dir = sys_get_temp_dir();
        $files = scandir($dir);  
      } else {
        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_ctv_topology.csv'" ;
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
                         $arr[0] =  'no new ethernet equipment';
                        array_push($arr_response['response'], $arr );
                      }
                       
                      }
                  }
                }
              }

            //-----------------------------------------------------------------
            $equipment_geom_update ="UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = CASE "." WHEN cubic_name LIKE '%Магистральный распределительный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Магістральний оптичний вузол%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Оптический узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Оптичний приймач%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Передатчик оптический%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Порт ОК%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Домовой узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Ответвитель магистральный%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Распределительный стояк%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Магистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Субмагистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Кросс-муфта%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." END FROM  ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
            //print($equipment_geom_update);
                $equipment_geometry_update_query =pg_query($db, $equipment_geom_update);
                //print( $equipment_geom_update);
            //-----------------------------------------------------------------
          	$sql = "CREATE TEMP TABLE tmp AS SELECT cubic_code, equipment_geom, cubic_name, cubic_street, cubic_house FROM ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL);	UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET mother_equipment_geom = tmp.equipment_geom,  cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;	 UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE ".$selectedCity."_ctv_topology.mother_equipment_geom IS NOT null AND ".$selectedCity."_ctv_topology.equipment_geom IS NOT NULL;";
          	$ret = pg_query($db, $sql);
                //print( $sql);
            //-----------------------------------------------------------------
            //update cubic_ou_name/cubic_ou_street/cubic_ou_house 
            $sql_ou_update = "CREATE TEMP TABLE tmp AS SELECT cubic_name, cubic_street, cubic_house FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IN (SELECT DISTINCT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL);UPDATE  ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_name, cubic_ou_house = tmp.cubic_name FROM tmp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code); DROP TABLE tmp;";
            $ret_ou_update = pg_query($db, $sql_ou_update);
            //--------------------------------------------------------------------
            // update link to Archive----------------------------------------
            $dir_arr_response = array();
                 $description_sql = "SELECT  cubic_name, cubic_code  FROM ".$selectedCity.".".$selectedCity."_ctv_topology;";
                  $description_ret = pg_query($db, $description_sql);
                  
                  if($description_ret) {
                         while ($row = pg_fetch_row($description_ret))  {

                          $description = array(
                              'cubic_name' => groupSelect($row[0])[3],
                              'cubic_code' => $row[1],

                            );
                          if($description['cubic_name'] !==null){
                            array_push($dir_arr_response, $description );
                            //print_r($description);
                           // echo'<br>';
                            topologyDirCreate($description, $selectedCity);
                            //echo'<hr>';
                          }
                          
                        }
                  }
                  $link_left_part = '"<a href="http://77.121.192.25/qgis-ck/tmp/archive/';
                  $link_right_part = '/" target="_blank">посилання на архів</a>"';


                  $archive_link_sql = "UPDATE $selectedCity"."."."$selectedCity"."_ctv_topology SET archive_link = CASE "." WHEN cubic_name like '%Магистральный распределительный узел%' THEN '$link_left_part"."$selectedCity"."/topology/mdod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптический узел%' THEN '$link_left_part"."$selectedCity"."/topology/nod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптичний приймач%' THEN '$link_left_part"."$selectedCity"."/topology/op/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Передатчик оптический%' THEN '$link_left_part"."$selectedCity"."/topology/ot/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Кросс-муфта%' THEN '$link_left_part"."$selectedCity"."/topology/cc/"."'||cubic_code||'"."$link_right_part' "."END;";
                  $archive_link_ret = pg_query($db, $archive_link_sql);
                 //print( $archive_link_sql);
                 //--------------------------------------------------------------------
}
print json_encode($arr_response);
pg_close($db); // Closing Connection
	
?>

