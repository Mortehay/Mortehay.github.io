<?php
//ini_set('display_errors', 1);
        
 	$selectedCity= $_POST['cityId'];  
       $tableId = $_POST['tempId'];
       $cableType = $_POST['cableType'];
       //$selectedCity= 'kiev';  
       //$tableId ='t_120';  


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
            $geojson = array( 'type' => 'FeatureCollection', 'features' => array());
              if ($cableType == 'cc') {
                $sql = "SELECT summ_tu, summ_contract_sum , summ_sub_contract, summ_acceptance_act, summ_approval_cartogram, summ_route_description, summ_cable_type, summ_archive_link, table_id,  notes2 , rezerve1 , rezerve2 , rezerve3, ST_AsGeoJSON(ST_Transform(geom_cable,3857) ),  ST_AsGeoJSON(ST_Transform(ST_StartPoint(geom_cable) ,3857) ) , ST_AsGeoJSON(ST_Transform(ST_EndPoint(geom_cable) ,3857) )  FROM ".$selectedCity.".".$selectedCity."_cable_channels WHERE table_id = '$tableId' AND geom_cable IS NOT NULL;  ";
                $ret = pg_query($db, $sql);
                  
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $cable = array(
                            'type' => 'Feature',
                            'properties' => array(
                              'summ_tu' => $row[0],
                              'summ_contract_sum' => $row[1],
                              'summ_sub_contract' => $row[2],
                              'summ_acceptance_act' => $row[3],
                              'summ_approval_cartogram' => $row[4],
                              'summ_route_description' => $row[5],
                              'summ_cable_type' => $row[6],
                              'summ_archive_link' => $row[7],
                              'table_id' => $row[8],
                              'notes2' => $row[9],
                              'rezerve1' => $row[10],
                              'rezerve2' => $row[11],
                              'rezerve3' => $row[12]
                            ),
                            'geometry'=>json_decode($row[13])
                            );
                          $startPoint = array(
                            'type' =>'Feature',
                            'properties' => array(
                              'summ_tu' => $row[0],
                              'summ_contract_sum' => $row[1],
                              'summ_sub_contract' => $row[2],
                              'summ_acceptance_act' => $row[3],
                              'summ_approval_cartogram' => $row[4],
                              'summ_route_description' => $row[5],
                              'summ_cable_type' => $row[6],
                              'summ_archive_link' => $row[7],
                              'table_id' => $row[8],
                              'notes2' => $row[9],
                              'rezerve1' => $row[10],
                              'rezerve2' => $row[11],
                              'rezerve3' => $row[12]
                            ),
                            'geometry' =>json_decode($row[14])
                            );
                          $endPoint = array(
                            'type' =>'Feature',
                            'properties' => array(
                              'summ_tu' => $row[0],
                              'summ_contract_sum' => $row[1],
                              'summ_sub_contract' => $row[2],
                              'summ_acceptance_act' => $row[3],
                              'summ_approval_cartogram' => $row[4],
                              'summ_route_description' => $row[5],
                              'summ_cable_type' => $row[6],
                              'summ_archive_link' => $row[7],
                              'table_id' => $row[8],
                              'notes2' => $row[9],
                              'rezerve1' => $row[10],
                              'rezerve2' => $row[11],
                              'rezerve3' => $row[12]
                            ),
                            'geometry' =>json_decode($row[15])
                            );
                        }
                      }
              }
              if ($cableType == 'pkp') {
                $sql = "SELECT table_id , cable_progect_link , cable_mount_date ,  cable_type , cable_short_type_description , cable_description ,  progect_number ,  cable_purpose , cubic_start_street , cubic_start_house_num,  cubic_start_house_entrance_num ,    cubic_end_street , cubic_end_house_num ,  cubic_end_house_entrance_num , total_cable_length, ST_AsGeoJSON(ST_Transform(geom_cable,3857) ),  ST_AsGeoJSON(ST_Transform(ST_StartPoint(geom_cable) ,3857) ) , ST_AsGeoJSON(ST_Transform(ST_EndPoint(geom_cable) ,3857) )  FROM ".$selectedCity.".".$selectedCity."_cable_air WHERE table_id = '$tableId' AND geom_cable IS NOT NULL;  ";
                $ret = pg_query($db, $sql);
                  
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $cable = array(
                            'type' => 'Feature',
                            'properties' => array(
                              'table_id' => $row[0],
                              'cable_progect_link' => $row[1],
                              'cable_mount_date' => $row[2],
                              'cable_type' => $row[3],
                              'cable_short_type_description' => $row[4],
                              'cable_description' => $row[5],
                              'progect_number' => $row[6],
                              'cable_purpose' => $row[7],
                              'rote_description' => $row[8].",буд.№ ".$row[9].",під.№ ".$row[10]." - ".$row[11].",буд.№ ".$row[12].",під.№ ".$row[13] ,
                              'total_cable_length' => $row[14]
                            ),
                            'geometry'=>json_decode($row[15])
                            );
                          $startPoint = array(
                            'type' =>'Feature',
                            'properties' => array(
                              'table_id' => $row[0],
                              'cable_progect_link' => $row[1],
                              'cable_mount_date' => $row[2],
                              'cable_type' => $row[3],
                              'cable_short_type_description' => $row[4],
                              'cable_description' => $row[5],
                              'progect_number' => $row[6],
                              'cable_purpose' => $row[7],
                              'rote_description' => $row[8].",буд.№ ".$row[9].",під.№ ".$row[10]." - ".$row[11].",буд.№ ".$row[12].",під.№ ".$row[13] ,
                              'total_cable_length' => $row[14]
                            ),
                            'geometry' =>json_decode($row[16])
                            );
                          $endPoint = array(
                            'type' =>'Feature',
                            'properties' => array(
                              'table_id' => $row[0],
                              'cable_progect_link' => $row[1],
                              'cable_mount_date' => $row[2],
                              'cable_type' => $row[3],
                              'cable_short_type_description' => $row[4],
                              'cable_description' => $row[5],
                              'progect_number' => $row[6],
                              'cable_purpose' => $row[7],
                              'rote_description' => $row[8].",буд.№ ".$row[9].",під.№ ".$row[10]." - ".$row[11].",буд.№ ".$row[12].",під.№ ".$row[13] ,
                              'total_cable_length' => $row[14]
                            ),
                            'geometry' =>json_decode($row[17])
                            );
                          }
                        }
                      }                  

                          
                          array_push($geojson['features'], $startPoint);
                          array_push($geojson['features'], $cable);
                          array_push($geojson['features'], $endPoint);
                 }   

	pg_close($db); // Closing Connection
	//print( $sql);
       print json_encode($geojson);
?>

