<?php
//ini_set('display_errors', 1);

      if ($_POST['selectedCity']) {
        $selectedCity= $_POST['selectedCity'];
      } else {
                $selectedCity = $_REQUEST['selectedCity'];
      } 

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

 
                 $sql = "SELECT DISTINCT cubic_pgs_addr FROM ".$selectedCity.".".$selectedCity."_ctv_topology order by cubic_pgs_addr; ";
                  $ret = pg_query($db, $sql);
                  $arr_response = array('response' => array());
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $arr = array(
                              'she' => $row[0]

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

