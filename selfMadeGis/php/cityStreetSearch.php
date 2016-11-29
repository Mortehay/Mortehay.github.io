<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['city'];  
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
  $arr_response = array('response' => array());

     $sql = "SELECT DISTINCT cubic_street FROM ".$selectedCity.".".$selectedCity."_buildings WHERE cubic_street IS NOT NULL";
           $ret = pg_query($db, $sql);
        if($ret) {

               while ($row = pg_fetch_row($ret))  {

                $arr = array(
                    'cubic_street' => $row[0],
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

