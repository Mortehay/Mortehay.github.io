<?php
//ini_set('display_errors', 1);
      if ($_POST['ctv_topology_dataView_city_eng']) {
        $selectedCity= $_POST['ctv_topology_dataView_city_eng'];
      } else {
        $selectedCity = $_REQUEST["selectedCity"];
      }

 	//$selectedCity = $_POST['ctv_topology_dataView_city_eng'];  
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
	

 
                 $sql = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL;";
                  $ret = pg_query($db, $sql);
                  $arr_response = array(
                    'node' => array(),
                    'links' => array()
                    );
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $description = array(
                              'cubic_city' => $row[0],
                              'cubic_street' => $row[1],
                              'cubic_house' => $row[2],
                              'cubic_name' => $row[3],
                            );

                          $connections = array(
                              'source' => $row[5],
                              'target' => $row[4],
                            );

                          //print_r( $arr);
                          array_push($arr_response['node'], $description );
                          array_push($arr_response['links'], $connections );
                          //array_push($arr_response, $arr);
                        }
                  }

	print json_encode($arr_response);
      }
	pg_close($db); // Closing Connection
	//print( $sql);
?>

