<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['city_building_data_eng'];  
	
      $linkStorage = "'/tmp/".$selectedCity."_buildings.csv'";
      $dir = sys_get_temp_dir();
      $files = scandir($dir);
           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=postgres password=postgres";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                //echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
	}

              
                  //-------------------------------------------------------------------------------------------------------------------
                  $response = "SELECT  e_mail, login_time FROM public.login ORDER BY login_time;";
                  $ret_response = pg_query($db, $response);
                  $arr_response = array('response' => array());
                  //$arr_response = array();
                  //-------------------------------------------------------------------------------------------------------------------
                      if($ret_response) {

                         while ($row = pg_fetch_row($ret_response))  {

                          $arr = array(
                              'e_mail' => $row[0],
                              'login_time' => substr($row[1], 0, 10)
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

