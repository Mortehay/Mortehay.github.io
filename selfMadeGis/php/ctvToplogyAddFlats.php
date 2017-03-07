<?php
	//ini_set('display_errors', 1);
	if ($_POST['ctv_city_flats_eng']) {
		$selectedCity= $_POST['ctv_city_flats_eng'];
	} else {
	        	$selectedCity = $_REQUEST['ctv_city_flats_eng'];
	}
	if ($_POST['she']) {
		$selectedShe= $_POST['she'];
	} else {
	        	$selectedShe= $_REQUEST['she'];
	} 
	//echo $selectedShe;
	if($selectedShe == 'виберіть ПГС') {$deleteNotselectedShe =''; $selectedShe = '';} 
	else {
		$deleteNotselectedShe= "DELETE FROM _city_hlam".$selectedCity."_ctv_topology_full  WHERE PGS_ADDR <> '".$selectedShe."'; ";
		$selectedShe = " AND cubic_pgs_addr  ='".$selectedShe."'  ";
	}
	//echo $selectedShe;		
	 if (file_exists("/tmp/".$selectedCity."_ctv_topology_full.csv")) {
	        $linkStorage = "'/tmp/".$selectedCity."_ctv_topology_full.csv'";
	        $dir = sys_get_temp_dir();
	        $files = scandir($dir);  
	      } else {
	        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_ctv_topology_full.csv'" ;
	        $dir = "/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/";
	        $files = scandir($dir);
	      }
	
	$host        = "host=127.0.0.1";
           	$port        = "port=5432";
          	$dbname      = "dbname=postgres";
           	$credentials = "user=simpleuser password=simplepassword";
           	$arr_response = array('response' => array());
           	$db = pg_connect( "$host $port $dbname $credentials"  );
           	//echo $selectedCity;
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
             	if ($files) {
	              foreach($files as $file) {
		             $str_file = (string)$file;
			             if ($str_file !== '.' && $str_file !== '..') {
			                     	 //print_r($str_file);
				             if ($str_file == $selectedCity."_ctv_topology_full.csv") {
				                        //print_r($str_file);
				             	$ctv_topology_full_temp_table ="CREATE  TEMP TABLE _city_hlam".$selectedCity."_ctv_topology_full (id serial, CITY varchar(100),STREET varchar(100), HOUSE varchar(100), FLAT varchar(100),CODE varchar(100),NAME varchar(100),PGS_ADDR varchar(100),OU_OP_ADDR varchar(100),OU_CODE varchar(100),DATE_REG varchar(100),COMENT varchar(100),UNAME varchar(100),NET_TYPE varchar(100),HOUSE_ID  varchar(100)); select copy_for_testuser('_city_hlam".$selectedCity."_ctv_topology_full(CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID)', ".$linkStorage.", ';', 'windows-1251');".$deleteNotselectedShe;
				             	//echo '<hr>'.$ctv_topology_full_temp_table.'<hr>';
				             	$data_upload_query = pg_query($db, $ctv_topology_full_temp_table);
				             	$add_flats_to_amplifiers="CREATE TEMP TABLE tmp AS select _full.code, (WITH RECURSIVE subordinates AS ( SELECT code, ou_code, name, street, house, flat FROM _city_hlam".$selectedCity."_ctv_topology_full WHERE code = _full.code UNION ALL SELECT e.code,e.ou_code,e.name, e.street, e.house, e.flat FROM _city_hlam".$selectedCity."_ctv_topology_full e INNER JOIN subordinates s ON s.code = e.ou_code ) select array_agg(min_max_flat)  from (SELECT subordinates.name,  concat(subordinates.street,' №', subordinates.house,'--',min(subordinates.flat::int) ,'/', max(subordinates.flat::int)) as min_max_flat FROM subordinates where textregexeq(replace(subordinates.flat, ' ', ''),'^[[:digit:]]+(\.[[:digit:]]+)?$') group by subordinates.name, subordinates.street, subordinates.house order by subordinates.name, subordinates.street, subordinates.house) as amplifier_flats) as flats  from _city_hlam".$selectedCity."_ctv_topology_full _full where _full.code in (select _full.code from _city_hlam".$selectedCity."_ctv_topology_full _full where _full.name = 'Домовой узел');  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology set flats = tmp.flats from tmp WHERE cubic_code = tmp.code; drop table tmp; drop table _city_hlam".$selectedCity."_ctv_topology_full;";
				             	//echo '<hr>'.$add_flats_to_amplifiers.'<hr>';
				             	$flats_add_query = pg_query($db, $add_flats_to_amplifiers);
				             	$view_flats = "SELECT cubic_street,cubic_house,cubic_code,cubic_name,cubic_pgs_addr,cubic_ou_op_addr,cubic_ou_code,cubic_date_reg,cubic_coment,cubic_uname,cubic_net_type, cubic_house_id,flats from ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name = 'Домовой узел' ".$selectedShe.";";// and flats is not null
				             	//echo '<hr>'.$view_flats.'<hr>';
				             	$flats_view_query = pg_query($db, $view_flats);
				             	if($flats_view_query) {
					                        $arr_response = array('response' => array());
					                         while ($row = pg_fetch_row($flats_view_query) )  {
					 
					                          $arr = array(
					                              'cubic_street' => $row[0],
					                              'cubic_house' => $row[1],
					                              'cubic_code' => $row[2],
					                              'cubic_name' => $row[3],
					                              'cubic_pgs_addr' => $row[4],
					                              'cubic_ou_op_addr' => $row[5],
					                              'cubic_ou_code' => $row[6],
					                              'cubic_date_reg' => $row[7],
					                              'cubic_coment' => $row[8],
					                              'cubic_uname' => $row[9],
					                              'cubic_net_type' => $row[10],
					                              'cubic_house_id' => $row[11],
					                              'flats' => $row[12]

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
             }
             print json_encode($arr_response);
	pg_close($db); // Closing Connection
?>