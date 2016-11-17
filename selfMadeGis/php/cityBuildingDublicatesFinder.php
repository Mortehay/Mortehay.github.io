<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['city_building_dublicates_finder_eng'];  

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
	}

 
                 $sql = "SELECT openstreet_addr_street, openstreet_addr_housenumber, cubic_street, cubic_house, cubic_house_id, ST_AsLatLonText(ST_Transform(building_geom_firstpoint, 4326) ) FROM ".$selectedCity.".".$selectedCity."_buildings WHERE cubic_house_id IN (SELECT cubic_house_id FROM  (SELECT openstreet_addr_street, openstreet_addr_housenumber,cubic_house_id, count(*) AS count FROM ".$selectedCity.".".$selectedCity."_buildings  WHERE cubic_house_id IS NOT NULL GROUP BY 1, openstreet_addr_street, openstreet_addr_housenumber,cubic_house_id  ORDER BY count) count WHERE count >1) ORDER BY cubic_house_id, openstreet_addr_street, openstreet_addr_housenumber";
                  $ret = pg_query($db, $sql);
                  $arr_response = array('response' => array());
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $arr = array(
                              'openstreet_addr_street' => $row[0],
                              'openstreet_addr_housenumber' => $row[1],
                              'cubic_street' => $row[2],
                              'cubic_house' => $row[3],
                              'cubic_house_id' => $row[4],
                              'building_geom_firstpoint' => $row[5]
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

