<?php
	//ini_set('display_errors', 1);
	if ($_POST['ctv_city_flats_eng']) {
		$selectedCity= $_POST['ctv_city_flats_eng'];
	} else {
	        	$selectedCity = $_REQUEST['ctv_city_flats_eng'];
	} 
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
				             	$ctv_topology_full_temp_table ="DROP TABLE IF EXISTS _city_hlam".".".$selectedCity."_ctv_topology_full; CREATE  TABLE  IF NOT EXISTS _city_hlam".".".$selectedCity."_ctv_topology_full (id serial, CITY varchar(100),STREET varchar(100), HOUSE varchar(100), FLAT varchar(100),CODE varchar(100),NAME varchar(100),PGS_ADDR varchar(100),OU_OP_ADDR varchar(100),OU_CODE varchar(100),DATE_REG varchar(100),COMENT varchar(100),UNAME varchar(100),NET_TYPE varchar(100),HOUSE_ID  varchar(100)); select copy_for_testuser('_city_hlam".".".$selectedCity."_ctv_topology_full(CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID)', ".$linkStorage.", ';', 'windows-1251');";
				             	//echo '<hr>'.$ctv_topology_full_temp_table.'<hr>';
				             	$data_upload_query = pg_query($db, $ctv_topology_full_temp_table);
				             	$add_flats_to_amplifiers="CREATE TEMP TABLE tmp AS WITH RECURSIVE children AS (SELECT city.code, city.ou_code, city.name, city.street, city.house, replace(city.flat, ' ', '') AS flat , 1 AS depth FROM _city_hlam".".".$selectedCity."_ctv_topology_full city WHERE ou_code in (select distinct code from _city_hlam".".".$selectedCity."_ctv_topology_full WHERE name ='Домовой узел') UNION ALL SELECT a.code, a.ou_code, a.name, a.street, a.house, replace(a.flat, ' ', ''), depth+1 AS depth FROM _city_hlam".".".$selectedCity."_ctv_topology_full a JOIN children b ON(a.ou_code = b.code)  WHERE  textregexeq(replace(a.flat, ' ', ''),'^[[:digit:]]+(\.[[:digit:]]+)?$') ) SELECT amplifiers.street AS amplifier_street, amplifiers.house AS amplifier_house, amplifiers.name AS amplifier_name, city.ou_code AS amplifier_code, city.code AS distribution_code,city.name AS distribution_name, min(cast(children.flat AS int)) AS min_flat,max(cast(children.flat AS int)) AS max_flat FROM children  LEFT JOIN _city_hlam".".".$selectedCity."_ctv_topology_full city ON children.ou_code = city.code LEFT JOIN (SELECT code, name, street, house FROM _city_hlam".".".$selectedCity."_ctv_topology_full WHERE name ='Домовой узел') amplifiers ON amplifiers.code = city.ou_code WHERE textregexeq(replace(children.flat, ' ', ''),'^[[:digit:]]+(\.[[:digit:]]+)?$') GROUP by amplifiers.street, amplifiers.house, amplifiers.name, city.ou_code,city.code, city.name; create temp table tmp_flats AS select amplifier_street, amplifier_house, amplifier_name, amplifier_code, string_agg(min_flat||'/'||max_flat, '; ') AS flats from tmp GROUP BY amplifier_street, amplifier_house, amplifier_name, amplifier_code; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology set flats = tmp_flats.flats from tmp_flats WHERE cubic_code = tmp_flats.amplifier_code; drop table tmp; drop table tmp_flats; ";
				             	//echo '<hr>'.$add_flats_to_amplifiers.'<hr>';
				             	$flats_add_query = pg_query($db, $add_flats_to_amplifiers);
				             	$view_flats = "SELECT cubic_street,cubic_house,cubic_flat,cubic_code,cubic_name,cubic_pgs_addr,cubic_ou_op_addr,cubic_ou_code,cubic_date_reg,cubic_coment,cubic_uname,cubic_net_type, cubic_house_id,flats from ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name = 'Домовой узел';";// and flats is not null
				             	//echo '<hr>'.$view_flats.'<hr>';
				             	$flats_view_query = pg_query($db, $view_flats);
				             	if($flats_view_query) {
					                        $arr_response = array('response' => array());
					                         while ($row = pg_fetch_row($flats_view_query) )  {
					 
					                          $arr = array(
					                              'cubic_street' => $row[0],
					                              'cubic_house' => $row[1],
					                              'cubic_flat' => $row[2],
					                              'cubic_code' => $row[3],
					                              'cubic_name' => $row[4],
					                              'cubic_pgs_addr' => $row[5],
					                              'cubic_ou_op_addr' => $row[6],
					                              'cubic_ou_code' => $row[7],
					                              'cubic_date_reg' => $row[8],
					                              'cubic_coment' => $row[9],
					                              'cubic_uname' => $row[10],
					                              'cubic_net_type' => $row[11],
					                              'cubic_house_id' => $row[12],
					                              'flats' => $row[13]

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