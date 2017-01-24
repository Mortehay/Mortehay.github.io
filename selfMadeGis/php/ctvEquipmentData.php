<?php
//ini_set('display_errors', 1);
        
 	$selectedCity= $_POST['city'];  
       $cubic_code = $_POST['cubic_code'];
       //$selectedCity= 'ukrainka';  
       //$cubic_code = "'4508106'";

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
            $json = array( 'equipment' => array());

                $sql = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_coment, archive_link, link   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE  cubic_code ='".$cubic_code."';";
                $ret = pg_query($db, $sql);
                  
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $arr = array(
                              'cubic_city' => $row[0],
                              'cubic_street' => $row[1],
                              'cubic_house' => $row[2],
                              'cubic_name' => $row[3],
                              'cubic_code' => $row[4],
                              'cubic_ou_code' => $row[5],
                              'cubic_coment' => $row[6],
                              'archive_link' => $row[7],
                              'link' => $row[8]
                            );
                          
                        }
                      }


                          array_push($json['equipment'], $arr);
                 }   

	pg_close($db); // Closing Connection
	//print( $sql);
       print json_encode($json);
?>

