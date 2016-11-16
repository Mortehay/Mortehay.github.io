<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['cable_channel_cable_dataView_city_eng'];  

           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=postgres password=postgres";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
	}

 
                 $sql = "SELECT summ_tu, summ_contract_sum , summ_sub_contract, summ_acceptance_act, summ_approval_cartogram, summ_route_description, summ_cable_type, summ_archive_link, table_id,  notes2 , rezerve1 , rezerve2 , rezerve3   FROM ".$selectedCity.".".$selectedCity."_cable_channels WHERE summ_tu IS NOT NULL AND summ_cable_type IS NOT NULL AND geom_cable IS NOT NULL ORDER BY id;  ";
                  $ret = pg_query($db, $sql);
                  $arr_response = array('response' => array());
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $arr = array(
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
                            );
                          //print_r( $arr);
                          array_push($arr_response['response'], $arr );
                          //array_push($arr_response, $arr);
                        }
                  }

     
      
     

	
	print json_encode($arr_response);
	pg_close($db); // Closing Connection
	//print( $sql);
?>

